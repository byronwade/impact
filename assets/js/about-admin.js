/* global jQuery, wp */
jQuery(document).ready(($) => {
	// Image Upload Handler
	function initImageUpload() {
		$(".upload-image").on("click", (e) => {
			e.preventDefault();
			const button = $(this);
			const uploader = wp
				.media({
					title: button.data("uploader-title") || "Select Image",
					button: {
						text: button.data("uploader-button-text") || "Use this image",
					},
					multiple: false,
				})
				.on("select", () => {
					const attachment = uploader.state().get("selection").first().toJSON();
					button.siblings('input[type="hidden"]').val(attachment.id);
					button.siblings(".image-preview").html(`<img src="${attachment.url}" style="max-width:150px;">`);
				})
				.open();
		});
	}

	// Story Paragraphs Handler
	$(".add-paragraph").on("click", () => {
		const index = $(".story-paragraphs p").length;
		const newParagraph = `
            <p>
                <label for="about_story_content_${index}">Paragraph ${index + 1}:</label><br>
                <textarea id="about_story_content_${index}" name="about_story_content[]" rows="3" class="widefat"></textarea>
            </p>
        `;
		$(".story-paragraphs").append(newParagraph);
	});

	// Features Handler
	$(".add-feature").on("click", () => {
		const index = $(".feature-item").length;
		const newFeature = `
            <div class="feature-item">
                <p>
                    <label for="feature_icon_${index}">Icon (Lucide icon name):</label><br>
                    <input type="text" id="feature_icon_${index}" name="about_features[${index}][icon]" class="widefat">
                </p>
                <p>
                    <label for="feature_title_${index}">Title:</label><br>
                    <input type="text" id="feature_title_${index}" name="about_features[${index}][title]" class="widefat">
                </p>
                <p>
                    <label for="feature_description_${index}">Description:</label><br>
                    <textarea id="feature_description_${index}" name="about_features[${index}][description]" rows="2" class="widefat"></textarea>
                </p>
            </div>
        `;
		$(".features-container").append(newFeature);
	});

	// Service Areas Handler
	$(".add-service-area").on("click", () => {
		const newArea = '<p><input type="text" name="service_areas[]" class="widefat"></p>';
		$(".service-areas-list").append(newArea);
	});

	// Shipping Services Handler
	$(".add-shipping-service").on("click", () => {
		const newService = '<p><input type="text" name="shipping_services[]" class="widefat"></p>';
		$(".shipping-services-list").append(newService);
	});

	// Business Hours Handler
	$(".add-hours").on("click", () => {
		const newHours = '<p><input type="text" name="business_hours[]" class="widefat"></p>';
		$(".business-hours").append(newHours);
	});

	// Initialize image upload handlers
	initImageUpload();

	// Add sortable functionality to lists
	$(".story-paragraphs, .features-container, .service-areas-list, .shipping-services-list, .business-hours").sortable({
		handle: "label",
		cursor: "move",
		opacity: 0.6,
		revert: true,
		update: (event, ui) => {
			// Optional: Update any indices or order if needed
		},
	});
});
