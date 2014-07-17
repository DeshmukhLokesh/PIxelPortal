/*
 * File: app/view/myprofile/Index.js
 *
 * This file was generated by Sencha Architect version 3.0.4.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 4.2.x library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 4.2.x. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('MyApp.view.myprofile.Index', {
    extend: 'Ext.form.Panel',
    alias: 'widget.myprofile',

    requires: [
        'Ext.form.FieldContainer',
        'Ext.Img',
        'Ext.form.field.File',
        'Ext.form.field.Display',
        'Ext.button.Button'
    ],

    border: false,
    height: 441,
    width: 960,
    layout: 'column',
    bodyPadding: 10,
    header: false,
    title: 'My Form',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            dockedItems: [
                {
                    xtype: 'fieldcontainer',
                    dock: 'left',
                    height: 159,
                    width: 202,
                    layout: 'anchor',
                    items: [
                        {
                            xtype: 'image',
                            height: 137,
                            itemId: 'userPicture',
                            width: 201,
                            src: './resources/user-icon.png'
                        },
                        {
                            xtype: 'filefield',
                            margin: '5 0 0 70',
                            width: 52,
                            name: 'fileUser',
                            buttonOnly: true,
                            buttonText: 'Change',
                            listeners: {
                                change: {
                                    fn: me.onFilefieldChange,
                                    scope: me
                                }
                            }
                        }
                    ]
                },
                {
                    xtype: 'fieldcontainer',
                    dock: 'left',
                    height: 120,
                    width: 761,
                    defaults: {
                        margin: '15 0 0 15'
                    },
                    layout: {
                        type: 'table',
                        columns: 2
                    },
                    items: [
                        {
                            xtype: 'displayfield',
                            colspan: 2,
                            name: 'fullName',
                            value: '.................',
                            fieldStyle: 'font-weight: bold;'
                        },
                        {
                            xtype: 'displayfield',
                            colspan: 2,
                            name: 'email',
                            value: '.................'
                        },
                        {
                            xtype: 'displayfield',
                            colspan: 2,
                            name: 'packageDetail'
                        },
                        {
                            xtype: 'textfield',
                            width: 350,
                            fieldLabel: 'First Name *',
                            labelWidth: 75,
                            msgTarget: 'under',
                            name: 'firstName',
                            allowBlank: false,
                            allowOnlyWhitespace: false,
                            maxLength: 30
                        },
                        {
                            xtype: 'textfield',
                            width: 350,
                            fieldLabel: 'Last Name *',
                            labelWidth: 75,
                            msgTarget: 'under',
                            name: 'lastName',
                            allowBlank: false,
                            allowOnlyWhitespace: false,
                            maxLength: 30
                        },
                        {
                            xtype: 'textfield',
                            margin: '25 0 0 15',
                            width: 350,
                            fieldLabel: 'Phone *',
                            labelWidth: 75,
                            msgTarget: 'under',
                            name: 'phone',
                            allowBlank: false,
                            allowOnlyWhitespace: false,
                            maxLength: 15
                        },
                        {
                            xtype: 'textfield',
                            margin: '25 0 0 15',
                            width: 350,
                            fieldLabel: 'Company *',
                            labelWidth: 75,
                            msgTarget: 'under',
                            name: 'company',
                            allowBlank: false,
                            allowOnlyWhitespace: false,
                            maxLength: 50
                        },
                        {
                            xtype: 'button',
                            id: 'saveprofile',
                            itemId: 'saveprofile',
                            margin: '25 0 0 95',
                            scale: 'medium',
                            text: 'Save Changes'
                        }
                    ]
                }
            ],
            listeners: {
                render: {
                    fn: me.onFormRender,
                    scope: me
                }
            }
        });

        me.callParent(arguments);
    },

    onFilefieldChange: function(filefield, value, eOpts) {
           var file = filefield.fileInputEl.dom.files[0];

         if (typeof FileReader !== "undefined" && (/image/i).test(file.type))
         {

             var reader = new FileReader();
             reader.onload =function(e){
              Ext.ComponentQuery.query('#userPicture')[0].setSrc(e.target.result);
                 //picture.setSrc(e.target.result);
                 // picture.dom.src = e.target.result;


             };

             reader.readAsDataURL(file);
         }
        else if(!(/image/i).test(file.type))
        {Ext.Msg.alert('Warning','You can only upload image files!');
         filefield.reset();
        }

    },

    onFormRender: function(component, eOpts) {


        var frm = this.getForm();

                Ext.Ajax.request({
                url:'rpcphp/clients/editForm.php',
                method:'POST',
                success:function(result,request){
                   json=Ext.decode(result.responseText,1);
                   frm.setValues(json); // form population
                                    // triggers change listeners
                     Ext.ComponentQuery.query('#userPicture')[0].setSrc('resources/ProfileImages/' + json.picture);
                }
            });


    }

});