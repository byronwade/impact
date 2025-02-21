/* global jQuery, wp */
jQuery(document).ready(($) => {
	// Image Upload Handler
	function initImageUpload() {
		$(".upload-image").on("click", function (e) {
			e.preventDefault();
			const button = $(this);
			const imageInput = button.siblings('input[type="hidden"]');
			const imagePreview = button.siblings(".image-preview");

			const frame = wp.media({
				title: "Select Image",
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

	// Why Choose Us Handler
	function initReasons() {
		$(".add-reason").on("click", () => {
			const newReason = `
                <p>
                    <input type="text" name="why_choose_us[]" class="widefat">
                    <button type="button" class="button remove-reason">Remove</button>
                </p>
            `;
			$(".reasons-list").append(newReason);
		});

		$(document).on("click", ".remove-reason", function () {
			$(this)
				.closest("p")
				.fadeOut(() => {
					$(this).closest("p").remove();
				});
		});

		$(".reasons-list").sortable({
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
		initImageUpload();
		initServicesGrid();
		initReasons();
		initPackages();
		initPolicies();
	}

	init();
});
