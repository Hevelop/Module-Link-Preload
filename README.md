# Magento 2 CSS Preload

Simple module that allows for asynchronous CSS loading in Magento 2

## Installation

1. Go to your Magento root directory
2. Run ```composer require m2-boilerplate/module-link-preload```


## Usage

To add assets to the block, provide the `assets` argument:

```
<referenceBlock name="head.csspreload">
    <arguments>
        <argument name="assets" xsi:type="array">
            <item name="unique_name" xsi:type="array">
                <item name="path" xsi:type="string">css/filename.css</item>
                <item name="as" xsi:type="string">style</item>
                <item name="attributes" xsi:type="array">
                    <item name="name1" xsi:type="string">value1</item>
                    <item name="name2" xsi:type="string">value2</item>
                </item>
            </item>
        </argument>
    </arguments>
</referenceBlock>
```

To modify the template of the generated `<link />` tags, provide a `link_template` argument, e.g.:

```
<referenceBlock name="head.csspreload">
    <arguments>
        <argument name="link_template" xsi:type="string"><![CDATA[<link rel="preload" as=":as:" href=":path:" :attributes: />]]></argument>
    </arguments>
</referenceBlock>
```

There are three variables that will be substituted: `:path:`, which will be replaced by the asset path, `:as:` which will be replaced by the asset type and `:attributes:` that will contain your `attributes` of your `assets` as HTML attributes.