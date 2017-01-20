<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:template name="captcha">
	<span id="capchaDiv" style="position:relative;display:inline-block">
		<img src="/capcha" class="capcha" id="capcha-image" alt="capcha"/>
		<div style="display:block;position:absolute;right:1px;top:2px">
			<a href="#"
			   onclick="$('#capcha-image').attr('src','/capcha?t='+(new Date().getTime()))"
			   style="text-decoration:none;color:#169971;font-size:14px;font-face:arial">
				<xsl:text>↻</xsl:text>
			</a>
		</div>
	</span>
</xsl:template>
</xsl:stylesheet>
