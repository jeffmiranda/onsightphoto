// !Events Grid
Pop.grid.Events = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pop-grid-events'
        ,url: Pop.config.connectorUrl
        ,baseParams: {
        	action: 'mgr/events/popEventGetList'
        }
        ,fields: ['id', 'name', 'default_inv_markup', 'default_tax', 'active']
        ,paging: true
        ,remoteSort: true
        ,sortBy: 'name'
        ,anchor: '97%'
        ,autoExpandColumn: 'name'
        ,save_action: 'mgr/events/popEventUpdateFromGrid'
        ,autosave: true
        ,columns: [{
            header: _('pop.id')
            ,hidden: true
            ,dataIndex: 'id'
            ,name: 'id'
        },{
            header: _('pop.name')
            ,dataIndex: 'name'
            ,name: 'name'
            ,sortable: true
            ,width: 100
            ,editor: { xtype: 'textfield' }
        },{
            header: _('pop.markup')
            ,dataIndex: 'default_inv_markup'
            ,name: 'default_inv_markup'
            ,sortable: true
            ,width: 100
            ,editor: {
	            xtype: 'numberfield'
	            ,decimalPrecision: 1
	            ,maxValue: 999.9
	            ,minValue: 0
	            ,allowBlank: false
	            ,qtip: _('pop.default_inv_markup_qtip')
	        }
        },{
            header: _('pop.tax')
            ,dataIndex: 'default_tax'
            ,name: 'default_tax'
            ,sortable: true
            ,width: 100
            ,editor: {
	            xtype: 'numberfield'
	            ,decimalPrecision: 1
	            ,maxValue: 999.9
	            ,minValue: 0
	            ,allowBlank: false
	            ,qtip: _('pop.default_tax_qtip')
	        }
        },{
            header: _('pop.active')
            ,dataIndex: 'active'
            ,sortable: true
            ,width: 50
            ,editor: {
	            xtype: 'pop-combo-active-status'
	            ,id: 'pop-combo-active-event'
	            ,renderer: true
	        }
        }]
        ,tbar:[{
            xtype: 'button'
            ,id: 'pop-create-event-button'
            ,text: _('pop.create_event')
            ,handler: { xtype: 'pop-window-event-create', blankValues: true }
        },{
            xtype: 'button'
            ,id: 'pop-update-event-button'
            ,text: _('pop.update')
            ,listeners: {
                'click': {fn: this.updateEvent, scope: this}
            }
        },{
            xtype: 'button'
            ,id: 'pop-event-active-toggle-button'
            ,text: _('pop.toggle_active')
            ,handler: function(btn,e) {
                this.toggleActive(btn,e);
            }
            ,scope: this
        },'->',{ // This defines the toolbar for the search
		    xtype: 'textfield' // Here we're defining the search field for the toolbar
		    ,id: 'event-search-filter'
		    ,emptyText: _('pop.search...')
		    ,listeners: {
		        'change': {fn:this.search,scope:this}
		        ,'render': {fn: function(cmp) {
		            new Ext.KeyMap(cmp.getEl(), {
		                key: Ext.EventObject.ENTER
		                ,fn: function() {
		                    this.fireEvent('change',this);
		                    this.blur();
		                    return true;
		                }
		                ,scope: cmp
		            });
		        },scope:this}
		    }
		},{
            xtype: 'button'
            ,id: 'clear-event-search'
            ,text: _('pop.clear_search')
            ,listeners: {
                'click': {fn: this.clearSearch, scope: this}
            }
        }]
    });
    Pop.grid.Events.superclass.constructor.call(this,config)
};
Ext.extend(Pop.grid.Events, MODx.grid.Grid, {
    search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,clearSearch: function() {
	    this.getStore().baseParams = {
            action: 'mgr/events/popEventGetList'
    	};
        Ext.getCmp('event-search-filter').reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,getMenu: function() { // MODX looks for getMenu when someone right-clicks on the grid
	    return [{
	        text: _('pop.update')
	        ,handler: this.updateEvent
	    },'-',{
	        text: _('pop.toggle_active')
	        ,handler: this.toggleActive
	    }];
	}
	,updateEvent: function(btn,e) {
		var selRow = this.getSelectionModel().getSelected();
        if (selRow.length <= 0) return false;
        //console.log(selRow.data);
	    if (!this.updateEventWindow) {
		    this.updateEventWindow = MODx.load({
		        xtype: 'pop-window-event-update'
		        ,record: selRow.data
		        ,listeners: {
		            'success': {
		            	fn:function(r){
		            		this.refresh();
		            		this.getSelectionModel().clearSelections(true);
		            	},scope:this
		            }
		        }
		    });
	    }
		this.updateEventWindow.setValues(selRow.data);
		this.updateEventWindow.show(e.target);
	}
	,toggleActive: function(btn,e) {
        var selRow = this.getSelectionModel().getSelected();
        if (selRow.length <= 0) return false;
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'mgr/events/popEventUpdate'
                ,id: selRow.data.id
                ,toggleActive: 1
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.refresh();
                    Ext.getCmp('pop-grid-events').refresh();
                },scope:this}
            }
        });
        return true;
    }
});
Ext.reg('pop-grid-events',Pop.grid.Events);


// !Create Event Window
Pop.window.CreateEvent = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('pop.create_event')
        ,width: '400'
        ,url: Pop.config.connectorUrl
        ,labelAlign: 'left'
        ,baseParams: {
            action: 'mgr/events/popEventCreate'
        }
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('pop.event')
            ,name: 'name'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: 'numberfield'
            ,fieldLabel: _('pop.default_inv_markup')
            ,name: 'default_inv_markup'
            ,anchor: '100%'
            ,decimalPrecision: 1
            ,maxValue: 999.9
            ,minValue: 0
            ,value: MODx.config.pop_default_inv_markup
            ,allowBlank: false
            ,qtip: _('pop.default_inv_markup_qtip')
        },{
            xtype: 'numberfield'
            ,fieldLabel: _('pop.default_tax')
            ,name: 'default_tax'
            ,anchor: '100%'
            ,decimalPrecision: 1
            ,maxValue: 999.9
            ,minValue: 0
            ,value: MODx.config.pop_default_tax
            ,allowBlank: false
            ,qtip: _('pop.default_tax_qtip')
        },{
            xtype: 'pop-combo-active-status'
            ,fieldLabel: _('pop.active')
            ,name: 'active'
            ,value: 1
            ,hiddenName: 'active'
            ,hiddenValue: 1
            ,anchor: '100%'
        }]
    });
    Pop.window.CreateEvent.superclass.constructor.call(this,config);
};
Ext.extend(Pop.window.CreateEvent,MODx.Window);
Ext.reg('pop-window-event-create',Pop.window.CreateEvent);


// !Update Window
Pop.window.UpdateEvent = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('pop.update_event')
        ,width: '400'
        ,url: Pop.config.connectorUrl
        ,labelAlign: 'left'
        ,baseParams: {
            action: 'mgr/events/popEventUpdate'
        }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('pop.event')
            ,name: 'name'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: 'numberfield'
            ,fieldLabel: _('pop.default_inv_markup')
            ,name: 'default_inv_markup'
            ,anchor: '100%'
            ,decimalPrecision: 1
            ,maxValue: 999.9
            ,minValue: 0
            ,allowBlank: false
            ,qtip: _('pop.default_inv_markup_qtip')
        },{
            xtype: 'numberfield'
            ,fieldLabel: _('pop.default_tax')
            ,name: 'default_tax'
            ,anchor: '100%'
            ,decimalPrecision: 1
            ,maxValue: 999.9
            ,minValue: 0
            ,allowBlank: false
            ,qtip: _('pop.default_tax_qtip')
        },{
            xtype: 'pop-combo-active-status'
            ,fieldLabel: _('pop.active')
            ,name: 'active'
            ,hiddenName: 'active'
            ,anchor: '100%'
        }]
    });
    Pop.window.UpdateEvent.superclass.constructor.call(this,config);
};
Ext.extend(Pop.window.UpdateEvent,MODx.Window);
Ext.reg('pop-window-event-update',Pop.window.UpdateEvent);