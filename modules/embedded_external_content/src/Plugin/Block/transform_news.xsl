<?xml version="1.0" encoding="UTF-8"?>
<!--
    Document   : transform_news.xsl
    Created on : 19 April 2017, 10:48
    Author     : idevelop
    Description:
        Purpose of transformation follows.
-->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" indent="yes"/>
    <xsl:param name="groupName" />
    <xsl:param name="allowable-length" select="250"/>
    <xsl:param name="totalNewsItems" select="count(rss/channel/item[category='Groups'] | rss/channel/item[category=$groupName])"/>
    
    <xsl:template match="/">
        <div class="row">
            <!-- Filter category -->
            <xsl:for-each select="rss/channel/item[category='Groups'] | rss/channel/item[category=$groupName]">
                <div class="col-md-4">
                    <div>
                        <xsl:element name="a">
                            <xsl:attribute name="href">
                                <xsl:value-of select="link"/>
                            </xsl:attribute>
                            <xsl:value-of select="title"/>
                        </xsl:element>
                    </div>
                    <p>
                        <xsl:value-of select="substring(description, 1, $allowable-length)"/>
                        <xsl:if test="string-length(description) > $allowable-length">
                            <xsl:text>...</xsl:text>
                        </xsl:if>
                    </p>
                </div>
            </xsl:for-each>
            <xsl:if test="$totalNewsItems = 0">
                <div class="col-md-12">
                    <p>no news</p>
                </div>
            </xsl:if>
        </div>
    </xsl:template>
</xsl:stylesheet>
