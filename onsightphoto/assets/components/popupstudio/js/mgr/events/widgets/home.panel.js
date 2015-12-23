Pop.panel.EventsHome = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>'+_('pop.popupstudio_events')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,items: [{
                title: _('pop.events')
                ,defaults: { autoHeight: true }
                ,items: [{
                    html: '<p>'+_('pop.events_desc')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'pop-grid-events'
                    ,cls: 'main-wrapper'
                    ,preventRender: true
                }]
            },{
	            title: _('pop.event_groups')
                ,defaults: { autoHeight: true }
                ,items: [{
                    html: '<p>'+_('pop.event_groups_desc')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
	                xtype: 'pop-cont-event-groups'
	                ,cls: 'main-wrapper'
                }]
            }]
            // only to redo the grid layout after the content is rendered
            // to fix overflow components' panels, especially when scroll bar is shown up
            ,listeners: {
                'afterrender': function(tabPanel) {
                    tabPanel.doLayout();
                }
            }
        }]
    });
    Pop.panel.EventsHome.superclass.constructor.call(this,config);
};
Ext.extend(Pop.panel.EventsHome,MODx.Panel);
Ext.reg('pop-panel-events-home',Pop.panel.EventsHome);