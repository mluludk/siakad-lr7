function generateWords(ln=5)
{
	var words =["The sky", "above", "the port","was", "the color of television", "tuned", "to", "a dead channel",  "All", "this happened", "more or less","I", "had", "the story", "bit by bit", "from various people", "and", "as generally", "happens", "in such cases", "each time", "it", "was", "a different story","It", "was", "a pleasure", "to", "burn"];
	var text = [];
	while(--ln) text.push(words[Math.floor(Math.random() * words.length)]);
	return text.join(" ");
}