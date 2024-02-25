select w.id as w_id,
     w.word as w_word,
     conjs.conjugation as conjugation,
     d.word_id as defns_word_id,
     d.defn as definition,
     e.defn_id as expressions_defn_id,
     e.source FROM words as w
     count(conjs.word_id) as cnt
inner JOIN
  defns as d ON w.id=d.word_id
inner JOIN
  verbs_conjugations as vc ON vc.word_id=w.id
inner JOIN
  conjugations as conjs ON conjs.id = vc.conj_id
left JOIN
  exprs as e ON e.defn_id=d.id
where d.word_id=:word_id
group by conj_id having cnt > 1


