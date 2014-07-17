/*
 * File: app/controller/Myplan.js
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

Ext.define('MyApp.controller.Myplan', {
    extend: 'Ext.app.Controller',

    models: [
        'MyPlan.Selected',
        'MyPlan.UnSelected'
    ],
    stores: [
        'MyPlan.Selected',
        'MyPlan.UnSelected'
    ],

    init: function(application) {
          this.control({"myplan gridpanel#gridSelectedPlan": { render: this.onRenderSelectedPlan } });

          this.control({ "myplan gridpanel#gridUnSelectedPlan": { render: this.onRenderUnselectedPlan } });


    },

    Index: function() {
        this.getApplication().getController('BaseSession').ValidateSession();
    },

    onRenderSelectedPlan: function(component, options) {
          component.getStore().load();
    },

    onRenderUnselectedPlan: function(component, options) {
        component.getStore().load();
    }

});
