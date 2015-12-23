Ext.onReady(function() {
    MODx.load({ xtype: 'pop-page-events-home'});
});
Pop.page.EventsHome = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'pop-panel-events-home'
            ,renderTo: 'pop-panel-events-home'
        }]
    });
    Pop.page.EventsHome.superclass.constructor.call(this,config);
};
Ext.extend(Pop.page.EventsHome,MODx.Component);
Ext.reg('pop-page-events-home',Pop.page.EventsHome);