BEGIN;

CREATE TABLE IF NOT EXISTS /*_*/rating_record (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  wiki_id INT NOT NULL,
  user_id INT NOT NULL,
  rating_id INT NOT NULL,
  created_time DATETIME NOT NULL,
  score TINYINT NOT NULL,
) /*$wgDBTableOptions*/;
	
CREATE INDEX /*i*/rating_score ON /*_*/rating_record  (created_time, rating_id, wiki_id, user_id);
CREATE INDEX /*i*/rating_user ON /*_*/rating_record  (rating_id, wiki_id, user_id, created_time);

COMMIT;

