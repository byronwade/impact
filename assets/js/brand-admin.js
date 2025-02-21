/* global jQuery, wp */
jQuery(document).ready(($) => {
	// Image Upload Handler
	function initImageUploader() {
		$(".acf-image-uploader").each(function () {
			const $uploader = $(this);
			const $input = $uploader.find('input[type="hidden"]');
			const $wrap = $uploader.find(".image-wrap");
			const $button = $uploader.find(".acf-button");

			// Show/Hide based on value
			function checkValue() {
				if ($input.val()) {
					$wrap.parent().removeClass("hide-if-value").addClass("show-if-value");
				} else {
					$wrap.parent().removeClass("show-if-value").addClass("hide-if-value");
				}
			}

			// Handle Upload
			$button.on("click", (e) => {
				e.preventDefault();

				const frame = wp.media({
					title: "Select Image",
					multiple: false,
					library: { type: "image" },
				});

				frame.on("select", () => {
					const attachment = frame.state().get("selection").first().toJSON();
					$input.val(attachment.id);
					$wrap.html(`<img src="${attachment.url}" alt="">`);
					checkValue();
				});

				frame.open();
			});

			// Remove image
			$wrap.on("click", ".acf-icon.-cancel", function (e) {
				e.preventDefault();
				$input.val("");
				$wrap.html("");
				checkValue();
			});

			// Initial check
			checkValue();
		});
	}

	// Repeater Field Handler
	function initRepeater() {
		$(".acf-repeater").each(function () {
			const $repeater = $(this);
			const $table = $repeater.find(".acf-table");
			const $tbody = $table.find(".acf-repeater-items");
			const $addButton = $repeater.find(".acf-actions .acf-button");

			// Get next row index
			function getNextIndex() {
				return $tbody.children(".acf-row").length;
			}

			// Update row numbers
			function updateRowNumbers() {
				$tbody.children(".acf-row").each(function (i) {
					$(this)
						.find(".acf-row-number")
						.text(i + 1);
				});
			}

			// Add Row Template
			function getRowTemplate(index) {
				return `
					<div class="acf-row">
						<div class="acf-row-handle order">
							<span class="reorder-handle">≡</span>
							<span class="acf-row-number">${index + 1}</span>
						</div>
						<div class="acf-fields">
							<div class="acf-field">
								<div class="acf-input">
									<input type="text" name="brand_features[]" placeholder="Enter feature">
								</div>
							</div>
						</div>
						<div class="acf-row-handle remove">
							<a class="acf-icon -minus small remove-row" href="#" data-event="remove-row" title="Remove"></a>
						</div>
					</div>
				`;
			}

			// Add Row
			$addButton.on("click", (e) => {
				e.preventDefault();
				const index = getNextIndex();
				const $row = $(getRowTemplate(index));
				$tbody.append($row);
				$row.hide().fadeIn();
				updateRowNumbers();
			});

			// Remove Row
			$tbody.on("click", ".remove-row", function (e) {
				e.preventDefault();
				const $row = $(this).closest(".acf-row");
				$row.fadeOut(function () {
					$(this).remove();
					updateRowNumbers();
				});
			});

			// Make sortable
			$tbody.sortable({
				handle: ".reorder-handle",
				helper: function (e, ui) {
					ui.children().each(function () {
						$(this).width($(this).width());
					});
					return ui;
				},
				start: function (e, ui) {
					ui.placeholder.height(ui.item.height());
				},
				update: updateRowNumbers,
			});
		});
	}

	// Initialize all functionality
	function init() {
		initImageUploader();
		initRepeater();
	}

	init();
});
