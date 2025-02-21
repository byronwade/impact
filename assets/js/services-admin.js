/* global jQuery, wp */
jQuery(document).ready(($) => {
	// Tab functionality
	$(".tab-button").on("click", function () {
		$(".tab-button").removeClass("active");
		$(".tab-content").removeClass("active");
		$(this).addClass("active");
		$('.tab-content[data-tab="' + $(this).data("tab") + '"]').addClass("active");
	});

	// Section order sorting
	$("#section-order").sortable({
		handle: ".dashicons-menu",
		update: function () {
			var order = [];
			$("#section-order li").each(function () {
				order.push($(this).data("section"));
			});
			$("#section-order-input").val(order.join(","));
		},
	});

	// Image upload functionality
	$(".upload-image").on("click", function (e) {
		e.preventDefault();
		var button = $(this);
		var imagePreview = button.siblings(".image-preview");
		var imageInput = button.siblings('input[type="hidden"]');

		var frame = wp.media({
			title: "Select or Upload Image",
			button: {
				text: "Use this image",
			},
			multiple: false,
		});

		frame.on("select", function () {
			var attachment = frame.state().get("selection").first().toJSON();
			imageInput.val(attachment.id);
			imagePreview.html('<img src="' + attachment.url + '" style="max-width:150px;">');
		});

		frame.open();
	});

	// Add/Remove functionality for dynamic lists
	function setupDynamicList(addButton, container, template) {
		$(addButton).on("click", function () {
			var index = container.children().length;
			var newItem = template.replace(/\{index\}/g, index);
			container.append(newItem);
		});

		$(document).on("click", ".remove-item", function () {
			$(this).closest(".dynamic-item").remove();
		});
	}

	// Setup for features list
	setupDynamicList(".add-feature", $(".features-list"), '<div class="dynamic-item"><input type="text" name="service_features[]" class="widefat" placeholder="Enter a feature"><button type="button" class="button remove-item">Remove</button></div>');

	// Setup for reasons list
	setupDynamicList(".add-reason", $(".reasons-list"), '<div class="dynamic-item"><input type="text" name="why_choose_us[]" class="widefat" placeholder="Enter a reason"><button type="button" class="button remove-item">Remove</button></div>');

	// Setup for policies list
	setupDynamicList(".add-policy", $(".policies-list"), '<div class="dynamic-item"><input type="text" name="service_policies[]" class="widefat" placeholder="Enter a policy"><button type="button" class="button remove-item">Remove</button></div>');

	// Make lists sortable
	$(".features-list, .reasons-list, .policies-list").sortable({
		handle: ".dashicons-menu",
		items: ".dynamic-item",
		cursor: "move",
	});

	// Services Grid Handler
	function initServicesGrid() {
		$(".add-service").on("click", () => {
			const index = $(".service-item").length;
			const newService = `
                <div class="service-item card">
                    <div class="card-header">
                        <h4>Service ${index + 1}</h4>
                        <button type="button" class="button remove-service">Remove</button>
                    </div>
                    <div class="card-body">
                        <p>
                            <label>Icon (Lucide icon name):</label><br>
                            <input type="text" name="services_grid[${index}][icon]" class="widefat">
                        </p>
                        <p>
                            <label>Title:</label><br>
                            <input type="text" name="services_grid[${index}][title]" class="widefat">
                        </p>
                        <p>
                            <label>Description:</label><br>
                            <textarea name="services_grid[${index}][description]" rows="3" class="widefat"></textarea>
                        </p>
                    </div>
                </div>
            `;
			$(".services-grid").append(newService);
		});

		$(document).on("click", ".remove-service", function () {
			$(this)
				.closest(".service-item")
				.fadeOut(() => {
					$(this).closest(".service-item").remove();
				});
		});

		$(".services-grid").sortable({
			handle: ".card-header",
			cursor: "move",
			opacity: 0.6,
		});
	}

	// Winterization Packages Handler
	function initPackages() {
		$(".add-package").on("click", () => {
			const index = $(".package-item").length;
			const newPackage = `
                <div class="package-item card">
                    <div class="card-header">
                        <h4>Package ${index + 1}</h4>
                        <button type="button" class="button remove-package">Remove</button>
                    </div>
                    <div class="card-body">
                        <p>
                            <label>Title:</label><br>
                            <input type="text" name="winterization_packages[${index}][title]" class="widefat">
                        </p>
                        <p>
                            <label>Description:</label><br>
                            <textarea name="winterization_packages[${index}][description]" rows="2" class="widefat"></textarea>
                        </p>
                        <div class="package-services">
                            <label>Services:</label>
                            <p>
                                <input type="text" name="winterization_packages[${index}][services][]" class="widefat">
                                <button type="button" class="button remove-package-service">Remove</button>
                            </p>
                            <button type="button" class="button add-package-service">Add Service</button>
                        </div>
                        <p>
                            <label>Price:</label><br>
                            <input type="text" name="winterization_packages[${index}][price]" class="widefat">
                        </p>
                        <p>
                            <label>Note:</label><br>
                            <input type="text" name="winterization_packages[${index}][note]" class="widefat">
                        </p>
                    </div>
                </div>
            `;
			$(".packages-list").append(newPackage);
		});

		$(document).on("click", ".remove-package", function () {
			$(this)
				.closest(".package-item")
				.fadeOut(() => {
					$(this).closest(".package-item").remove();
				});
		});

		$(document).on("click", ".add-package-service", function () {
			const packageIndex = $(this).closest(".package-item").index();
			const newService = `
                <p>
                    <input type="text" name="winterization_packages[${packageIndex}][services][]" class="widefat">
                    <button type="button" class="button remove-package-service">Remove</button>
                </p>
            `;
			$(this).closest(".package-services").find("label").after(newService);
		});

		$(document).on("click", ".remove-package-service", function () {
			$(this)
				.closest("p")
				.fadeOut(() => {
					$(this).closest("p").remove();
				});
		});

		$(".packages-list").sortable({
			handle: ".card-header",
			cursor: "move",
			opacity: 0.6,
		});
	}

	// Service Policies Handler
	function initPolicies() {
		$(".add-policy").on("click", () => {
			const newPolicy = `
                <p>
                    <input type="text" name="service_policies[]" class="widefat">
                    <button type="button" class="button remove-policy">Remove</button>
                </p>
            `;
			$(".policies-list").append(newPolicy);
		});

		$(document).on("click", ".remove-policy", function () {
			$(this)
				.closest("p")
				.fadeOut(() => {
					$(this).closest("p").remove();
				});
		});

		$(".policies-list").sortable({
			cursor: "move",
			opacity: 0.6,
		});
	}

	// Initialize all functionality
	function init() {
		initServicesGrid();
		initPackages();
		initPolicies();
	}

	init();
});
