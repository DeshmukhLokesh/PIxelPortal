/*
 * File: app/view/login/Index.js
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

Ext.define('MyApp.view.login.Index', {
    extend: 'Ext.form.Panel',
    alias: 'widget.login',

    requires: [
        'Ext.panel.Panel',
        'Ext.form.field.Text',
        'Ext.form.Label',
        'Ext.button.Button',
        'Ext.form.field.Display'
    ],

    align: 'center;',
    border: false,
    height: 238,
    style: '',
    width: 364,
    bodyBorder: false,
    bodyPadding: '',
    frameHeader: false,
    title: 'Login',
    titleAlign: 'center',

    layout: {
        type: 'table',
        columns: 2
    },

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'panel',
                    margin: '5 5 0 5',
                    header: false,
                    title: 'My Panel',
                    items: [
                        {
                            xtype: 'textfield',
                            margin: '25 15 15 15',
                            width: 320,
                            name: 'user',
                            allowBlank: false,
                            allowOnlyWhitespace: false,
                            blankText: 'Email is required field',
                            emptyText: 'Email',
                            vtype: 'email',
                            vtypeText: 'please enter an email address'
                        },
                        {
                            xtype: 'textfield',
                            margin: '5 15 15 15',
                            width: 321,
                            name: 'password',
                            inputType: 'password',
                            allowBlank: false,
                            allowOnlyWhitespace: false,
                            blankText: 'Password is required field',
                            emptyText: 'Password'
                        },
                        {
                            xtype: 'label',
                            itemId: 'loginErrorBox',
                            margin: '5 15 15 15',
                            style: 'color:red;',
                            text: ''
                        },
                        {
                            xtype: 'button',
                            formBind: true,
                            id: 'submit',
                            itemId: 'submit',
                            margin: '10 15 15 235',
                            width: 70,
                            text: 'Login'
                        },
                        {
                            xtype: 'displayfield',
                            margin: '5 10 10 10',
                            value: '<span style=\'color:green;\'><a href=\'#forgotpassword\'>Forgot your password?</a></span>'
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }

});