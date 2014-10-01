BEGIN;

CREATE TABLE IF NOT EXISTS /*_*/moegirl_rating_records (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  wiki_id INT NOT NULL,
  user_id VARCHAR(100) NOT NULL,
  rating_id INT NOT NULL,
  created_time DATETIME NOT NULL,
  score TINYINT NOT NULL
) /*$wgDBTableOptions*/;
	
CREATE INDEX /*i*/rating_score ON /*_*/moegirl_rating_records  (created_time, rating_id, wiki_id, user_id);
CREATE INDEX /*i*/rating_user ON /*_*/moegirl_rating_records  (rating_id, wiki_id, user_id, created_time);

COMMIT;

