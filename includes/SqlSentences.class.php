<?php
class SqlSentences {
	public static $getAverageScoreSentence = 'SELECT SUM(score) score, COUNT(score) users FROM %srating_record WHERE wiki_id= %d AND rating_id= %d AND DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= created_time;';
	public static $hasRatingTodaySentence = 'SELECT id from %srating_record WHERE user_id = %d AND wiki_id = %d AND rating_id = %d AND DATE(created_time) = CURRENT_DATE();';
}
