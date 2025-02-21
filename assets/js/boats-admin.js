/* global jQuery, wp */
jQuery(document).ready(($) => {
	// Image Upload Handler
	function initImageUpload() {
		$(document).on("click", ".upload-image", function (e) {
			e.preventDefault();
			const button = $(this);
			const imageInput = button.siblings('input[type="hidden"]');
			const imagePreview = button.siblings(".image-preview");

			const frame = wp.media({
				title: "Select Boat Image",
				multiple: false,
				library: { type: "image" },
			});

			frame.on("select", () => {
				const attachment = frame.state().get("selection").first().toJSON();
				imageInput.val(attachment.id);
				imagePreview.html(`<img src="${attachment.url}" alt="">`);
			});

			frame.open();
		});
	}

	// Manufacturers Handler
	function initManufacturers() {
		$(".add-manufacturer").on("click", () => {
			const newManufacturer = `
                <p>
                    <input type="text" name="boat_manufacturers[]" class="widefat">
                    <button type="button" class="button remove-manufacturer">Remove</button>
                </p>
            `;
			$(".manufacturers-list").append(newManufacturer);
		});

		$(document).on("click", ".remove-manufacturer", function () {
			$(this)
				.closest("p")
				.fadeOut(() => {
					$(this).closest("p").remove();
				});
		});

		$(".manufacturers-list").sortable({
			cursor: "move",
			opacity: 0.6,
		});
	}

	// Boats Handler
	function initBoats() {
		$(".add-boat").on("click", () => {
			const index = $(".boat-item").length;
			const newBoat = `
                <div class="boat-item card">
                    <div class="card-header">
                        <h4>Boat ${index + 1}</h4>
                        <button type="button" class="button remove-boat">Remove</button>
                    </div>
                    <div class="card-body">
                        <p>
                            <label>Name:</label><br>
                            <input type="text" name="boats[${index}][name]" class="widefat">
                        </p>
                        <p>
                            <label>Manufacturer:</label><br>
                            <input type="text" name="boats[${index}][manufacturer]" class="widefat">
                        </p>
                        <p>
                            <label>Model:</label><br>
                            <input type="text" name="boats[${index}][model]" class="widefat">
                        </p>
                        <p>
                            <label>Year:</label><br>
                            <input type="number" name="boats[${index}][year]" class="widefat">
                        </p>
                        <p>
                            <label>Type:</label><br>
                            <input type="text" name="boats[${index}][type]" class="widefat">
                        </p>
                        <p>
                            <label>Price:</label><br>
                            <input type="number" name="boats[${index}][price]" class="widefat">
                        </p>
                        <p>
                            <label>Status:</label><br>
                            <select name="boats[${index}][status]" class="widefat">
                                <option value="Available">Available</option>
                                <option value="On Order">On Order</option>
                                <option value="Sold">Sold</option>
                            </select>
                        </p>
                        <p>
                            <label>Condition:</label><br>
                            <select name="boats[${index}][condition]" class="widefat">
                                <option value="NEW">New</option>
                                <option value="USED">Used</option>
                            </select>
                        </p>
                        <p>
                            <label>Image:</label><br>
                            <input type="hidden" name="boats[${index}][image]" class="widefat">
                            <button type="button" class="button upload-image">Upload Image</button>
                            <div class="image-preview"></div>
                        </p>
                    </div>
                </div>
            `;
			$(".boats-list").append(newBoat);
		});

		$(document).on("click", ".remove-boat", function () {
			$(this)
				.closest(".boat-item")
				.fadeOut(() => {
					$(this).closest(".boat-item").remove();
				});
		});

		$(".boats-list").sortable({
			handle: ".card-header",
			cursor: "move",
			opacity: 0.6,
			update: function () {
				// Update boat numbers after sorting
				$(".boat-item").each((index, element) => {
					$(element)
						.find("h4")
						.text(`Boat ${index + 1}`);
				});
			},
		});
	}

	// Initialize all functionality
	function init() {
		initImageUpload();
		initManufacturers();
		initBoats();
	}

	init();
});
