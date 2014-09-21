<?php
class SqlSentences {
	public static $ratingRecordTable = 'moegirl_rating_record';

	public static $getAverageScoreSentence = 'SELECT SUM(score) score, COUNT(score) users FROM %s WHERE wiki_id= %d AND rating_id= %d AND DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= created_time;';

	public static $hasRatingTodaySentence = 'SELECT id from %s WHERE user_id = %d AND wiki_id = %d AND rating_id = %d AND DATE(created_time) = CURRENT_DATE();';

	public static $hasRatedWikiSentence = 'SELECT id FROM %s WHERE user_id = %d AND wiki_id = %d AND rating_id = %d;';

	public static $rateWikiUpdateSentence = 'UPDATE %s SET score = %d, created_time = NOW() WHERE user_id = %d AND wiki_id = %d AND rating_id = %d;';

	public static $rateWikiInsertSentence = 'INSERT INTO %s(wiki_id, user_id, rating_id, created_time, score) VALUES (%d, %d, %d, NOW(), %d);';

}
