ALTER TABLE `experiments` ADD COLUMN `team_group` int(10) unsigned NOT NULL AFTER `team`;
UPDATE `experiments` SET `team_group`=(SELECT `id` FROM `team_groups` ORDER BY ID LIMIT 1);
