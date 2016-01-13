<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:template
			match="node()[descendant-or-self::*[local-name(.) = 'Security_SignOut'] or descendant-or-self::*[local-name(.) = 'marker1'] or descendant-or-self::*[local-name(.) = 'dumbo'] or descendant-or-self::*[local-name(.) = 'boardOffPoints'] or descendant-or-self::*[@*] or descendant-or-self::*[string-length(normalize-space(.)) &gt; 0]]">
		<xsl:copy>
			<xsl:apply-templates select="@*"/>
			<xsl:apply-templates select="node()"/>
		</xsl:copy>
	</xsl:template>
	<xsl:template match="text()">
		<xsl:value-of select="."/>
	</xsl:template>
	<xsl:template match="@*">
		<xsl:attribute name="{name()}"><xsl:value-of select="."/>
		</xsl:attribute>
	</xsl:template>
</xsl:stylesheet>