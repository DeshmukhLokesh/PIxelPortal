/*
 * File: app/controller/Txnhistory.js
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

Ext.define('MyApp.controller.Txnhistory', {
    extend: 'Ext.app.Controller',

    stores: [
        'TraxnHistory'
    ],

    Index: function() {
        this.getApplication().getController('BaseSession').ValidateSession();
    },

    init: function(application) {
           this.control({"txnhistory gridpanel#gridPanelTxnHistory": { render: this.onRenderTxnHistory } });
    },

    onRenderTxnHistory: function(component, options) {
        component.getStore().load();
    }

});
