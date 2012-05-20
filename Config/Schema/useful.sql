-- overview of token frequencies
SELECT	t.value,
	c.label,
	tc.count
FROM	bayes_tokens t
INNER JOIN
	bayes_token_counters tc
	ON	t.id = tc.bayes_token_id
INNER JOIN
	bayes_classes c
	ON	tc.bayes_class_id = c.id
ORDER BY
	t.value