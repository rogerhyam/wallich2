create table wallich.fragments
SELECT 
	nid,
	vid,
	title,
	body.body_value,
    fn.field_notes_value as notes,
	field_image.field_image_width as image_width,
	field_image.field_image_height as image_height,
	field_image.field_image_fid as image_fid,
	field_catalogue_page.field_catalogue_page_target_id as page_nid,
	import_id.field_import_id_value as import_id,
	field_page_x.field_page_x_value as page_x,
	field_page_y.field_page_y_value as page_y,
	entry.field_entry_number_value as entry_number,
    entry_qualifier.field_entry_number_qualifier_value as entry_number_qualifier,
    field_associated_entry.field_associated_entry_target_id as entry_nid
FROM 
	wallich_drupal.node as n
LEFT JOIN
	wallich_drupal.field_data_body as body on body.entity_id = n.nid
LEFT JOIN 
	wallich_drupal.field_data_field_image as field_image on field_image.entity_id = n.nid
LEFT JOIN 
	wallich_drupal.field_data_field_catalogue_page as field_catalogue_page on field_catalogue_page.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_import_id as import_id on import_id.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_page_x as field_page_x on field_page_x.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_page_y as field_page_y on field_page_y.entity_id = n.nid 
LEFT JOIN 
	wallich_drupal.field_data_field_entry_number as entry on entry.entity_id = n.nid
LEFT JOIN 
	wallich_drupal.field_data_field_entry_number_qualifier as entry_qualifier on entry_qualifier.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_associated_entry as field_associated_entry on field_associated_entry.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_notes as fn on fn.entity_id = n.nid
WHERE `type` = 'fragment'