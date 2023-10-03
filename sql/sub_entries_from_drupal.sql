CREATE TABLE wallich.sub_entries
SELECT
	entry.field_entry_number_value as entry_number,
    entry_qualifier.field_entry_number_qualifier_value as entry_number_qualifier,
    field_order.field_order_value as 'order',
	n.nid as drupal_nid,
	n.title as title,
    b.body_value as verbatim,
    fn.field_notes_value as notes,
    tn.field_taxon_name_value as taxon_name,
    tna.field_taxon_authority_tid as author_tid,
    authority.`name` as author_name,
    authority.`description` as author_description,
    ipni.field_ipni_id_value as ipni_id,
    import_id.field_import_id_value as import_id,
    page_id.field_catalogue_page_target_id as page_nid,
    ed.field_editorial_status_value as editorial_status,
    field_year.field_year_value as 'year',
    field_location.field_location_tid as location_tid,
    field_cultivated_source.field_cultivated_source_tid as garden_tid,
    field_herbarium_source.field_herbarium_source_tid as herbarium_tid,
    field_collector.field_collector_tid as collector_tid
FROM
	wallich_drupal.node as n
LEFT JOIN
	wallich_drupal.field_data_body as b on b.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_notes as fn on fn.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_taxon_name as tn on tn.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_taxon_authority as tna on tna.entity_id = n.nid
LEFT JOIN 
	wallich_drupal.taxonomy_term_data as authority on authority.tid = tna.field_taxon_authority_tid
LEFT JOIN 
	wallich_drupal.field_data_field_ipni_id as ipni on ipni.entity_id = n.nid
LEFT JOIN 
	wallich_drupal.field_data_field_entry_number as entry on entry.entity_id = n.nid
LEFT JOIN 
	wallich_drupal.field_data_field_entry_number_qualifier as entry_qualifier on entry_qualifier.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_import_id as import_id on import_id.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_catalogue_page as page_id on page_id.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_editorial_status as ed on ed.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_order as field_order on field_order.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_year as field_year on field_year.entity_id = n.nid
LEFT JOIN 
	wallich_drupal.field_data_field_location as field_location on field_location.entity_id = n.nid
LEFT JOIN 
	wallich_drupal.field_data_field_cultivated_source as field_cultivated_source on field_cultivated_source.entity_id = n.nid
LEFT JOIN 
	wallich_drupal.field_data_field_herbarium_source as field_herbarium_source on field_herbarium_source.entity_id = n.nid
LEFT JOIN 
	wallich_drupal.field_data_field_collector as field_collector on field_collector.entity_id = n.nid
WHERE n.`type` = 'sub_entry';

