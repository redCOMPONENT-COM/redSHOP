SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------
-- Table `#__redshop_media`
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_media', 'scope', 'scope', 'VARCHAR(100) NOT NULL DEFAULT ""');
CALL redSHOP_Index_Add('#__redshop_media', '#__rs_idx_media_scope', '(`scope` ASC)');

SET FOREIGN_KEY_CHECKS = 1;