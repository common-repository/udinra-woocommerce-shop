(function () {
    'use strict';

    tinymce.PluginManager.add('udinra_wooshop_subscribe', function (editor, url) {
        editor.addButton('udinra_wooshop_subscribe', {
            title: 'Udinra WooShop Button',
            image: url + '/../image/wooshop.png',

            onclick: function () {
                editor.windowManager.open({
                    title: 'Udinra WooCommerce Shop Configuration',
                    body: [
                       {
                            type: 'listbox',
                            name: 'sort',
                            label: 'Default Order',
                            values: [
                                {text: 'Popularity', value: 'sales'},
                                {text: 'Highest Rated', value: 'rated'},
                                {text: 'Most Reviewed', value: 'review'},								
                                {text: 'Lowest Price', value: 'lowprice'},
								{text: 'Highest Price', value: 'highprice'},								
                                {text: 'Newest First', value: 'newest'},
                                {text: 'Oldest First', value: 'oldest'}								
                            ]
                        },
                        {
                            type: 'listbox',
                            name: 'image',
                            label: 'Thumnail Size',
                            values: [
                                {text: 'Small', value: 'small'},
                                {text: 'Medium', value: 'medium'}
                            ]
                        },				
                        {
                            type: 'listbox',
                            name: 'show',
                            label: 'Show',
                            values: [
                                {text: 'Product Title', value: 'first'},
                                {text: 'Average Rating', value: 'second'},
								{text: 'Review Count', value: 'third'},
								{text: 'Title + Rating', value: 'fourth'},
								{text: 'Title + Review', value: 'fifth'},
								{text: 'Rating + Review', value: 'sixth'},
								{text: 'All of them', value: 'seventh'},
                            ]
                        },
                        {
                            type: 'listbox',
                            name: 'purchase',
                            label: 'Add to Cart button',
                            values: [
                                {text: 'Enable', value: 'true'},
                                {text: 'Disable', value: 'false'}
                            ]
                        },
						{
                            type: 'textbox',
							size: 40,
                            name: 'downcount',
                            label: 'Products per page',
                        }								
                    ],
                    onsubmit: function (e) {
                        editor.insertContent('[udinra_wooshop sort="' + e.data.sort 
												+ '" show="' + e.data.show
												+ '" purchase="' + e.data.purchase
												+ '" image="' + e.data.image
												+ '" downcount="' + e.data.downcount
												+ '" ]' );
                    }
                });
            }
        });
    });
})();


