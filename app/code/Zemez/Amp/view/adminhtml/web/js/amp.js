TMAMP = function()
{
	var $this = this;
	var ampTag = 'amp=1';

	$this.url = window.location;

	$this.getAmpUrl = function(url)
	{
		if (!url) url = $this.url;

		if (url.indexOf(ampTag) == -1) {
			url += (url.indexOf('?') == -1) ? '?' : '&';
			url += ampTag;
		}
		return url;
	}

	$this.redirectToAmp = function(url)
	{
		var ampUrl = $this.getAmpUrl(url)
		window.location = ampUrl;
	}

}