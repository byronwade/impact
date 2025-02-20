const { registerPlugin } = wp.plugins;
const { PluginSidebar } = wp.editPost;
const { TextControl, PanelBody } = wp.components;
const { useSelect, useDispatch } = wp.data;
const { __ } = wp.i18n;

function TemplateSettings() {
	const meta = useSelect((select) => {
		return select("core/editor").getEditedPostAttribute("meta");
	}, []);

	const { editPost } = useDispatch("core/editor");

	return (
		<PluginSidebar name="template-settings" title={__("Template Settings", "wades")}>
			<PanelBody>
				<TextControl
					label={__("Hero Title", "wades")}
					value={meta._hero_title || ""}
					onChange={(value) => {
						editPost({ meta: { ...meta, _hero_title: value } });
					}}
				/>
				{/* Add more controls */}
			</PanelBody>
		</PluginSidebar>
	);
}

registerPlugin("wades-template-settings", {
	render: TemplateSettings,
	icon: "admin-appearance",
});
