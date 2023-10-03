CREATE TABLE wallich.entries
SELECT
	entry.field_entry_number_value as entry_number,
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
    page_id.field_catalogue_page_target_id as page_node_id,
    ed.field_editorial_status_value as editorial_status
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
	wallich_drupal.field_data_field_import_id as import_id on import_id.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_catalogue_page as page_id on page_id.entity_id = n.nid
LEFT JOIN
	wallich_drupal.field_data_field_editorial_status as ed on ed.entity_id = n.nid
WHERE n.`type` = 'entry';

