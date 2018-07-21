<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<!--
	    This XSLT transformation will remove empty nodes from the outgoing request.
	    The Amadeus web services server seems to be very sensitive about the presence of empty nodes,
	    resulting in soapfaults when they are not removed.

	    On the other hand, in some contexts, empty nodes are REQUIRED.

	    This XSLT transformation by default removes all empty nodes except for the ones that are mentioned in the first row.
	    Exceptions currently are:
	    - Security_SignOut
	    - marker1 (PNR_AddMultiElements)
	    - dumbo
	    - boardOffPoints
	    - originDestination
	    - markerRoomStayData, markerGlobalBookingInfo, markerRoomstayQuery, marker, markerOfExtra (Offer_ConfirmHotelOffer)
	    - SalesReports_DisplayQueryReport (can be an empty request)
	    - fopReference, authorisationSupplementaryData, dummy (FOP_CreateFormOfPayment)

	    Author: Dieter Devlieghere <dermikagh@gmail.com>
	 -->
	<xsl:template
			match="node()[descendant-or-self::*[local-name(.) = 'Security_SignOut'] or descendant-or-self::*[local-name(.) = 'marker1'] or descendant-or-self::*[local-name(.) = 'dumbo'] or descendant-or-self::*[local-name(.) = 'boardOffPoints'] or descendant-or-self::*[local-name(.) = 'originDestination'] or descendant-or-self::*[local-name(.) = 'markerRoomStayData'] or descendant-or-self::*[local-name(.) = 'markerGlobalBookingInfo'] or descendant-or-self::*[local-name(.) = 'markerRoomstayQuery'] or descendant-or-self::*[local-name(.) = 'marker'] or descendant-or-self::*[local-name(.) = 'markerOfExtra'] or descendant-or-self::*[local-name(.) = 'SalesReports_DisplayQueryReport'] or descendant-or-self::*[local-name(.) = 'fopReference'] or descendant-or-self::*[local-name(.) = 'authorisationSupplementaryData'] or descendant-or-self::*[local-name(.) = 'dummy'] or descendant-or-self::*[@*] or descendant-or-self::*[string-length(normalize-space(.)) &gt; 0]]">
		<xsl:copy>
			<xsl:apply-templates select="@*"/>
			<xsl:apply-templates select="node()"/>
		</xsl:copy>
	</xsl:template>
	<xsl:template match="text()">
		<xsl:value-of select="."/>
	</xsl:template>
	<xsl:template match="@*">
		<xsl:attribute name="{local-name()}"><xsl:value-of select="."/>
		</xsl:attribute>
	</xsl:template>
	<xsl:template match="*/text()[normalize-space()]">
		<xsl:value-of select="normalize-space()"/>
	</xsl:template>
	<xsl:template match="*/text()[not(normalize-space())]" />
</xsl:stylesheet>
