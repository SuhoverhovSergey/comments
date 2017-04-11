CREATE TABLE `comment` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `text` VARCHAR(1000) NOT NULL,
  `left_key` INT(10) NOT NULL DEFAULT 0,
  `right_key` INT(10) NOT NULL DEFAULT 0,
  `level` INT(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX left_key (left_key, right_key, level)
);
