var Pop = function(config) {
    config = config || {};
    Pop.superclass.constructor.call(this,config);
};
Ext.extend(Pop,Ext.Component,{
    page:{}, window:{}, grid:{}, tree:{}, container:{}, panel:{}, combo:{}, config:{}, tabs:{}, store:{}, toolbar:{}
});
Ext.reg('pop',Pop);
Pop = new Pop();


/*
 * Here we're defining some common components used by many
 * of the widgets in the Popup Studio.
 */
 
// !Active Combobox
Pop.combo.ActiveStatus = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        store: new Ext.data.ArrayStore({
            fields: ['value','display']
            ,data: [
                [1,'Yes']
                ,[0,'No']
            ]
        })
        ,mode: 'local'
        ,displayField: 'display'
        ,valueField: 'value'
    });
    
    Pop.combo.ActiveStatus.superclass.constructor.call(this, config);
};
Ext.extend(Pop.combo.ActiveStatus, MODx.combo.ComboBox);
Ext.reg('pop-combo-active-status', Pop.combo.ActiveStatus);

// !Quick tips! Creates a quick tip on all form fields :)
Ext.override(Ext.form.Field, {
	afterRender : Ext.form.Field.prototype.afterRender.createSequence(function() {
		var qt = this.qtip;
	    if (qt) {
		    Ext.QuickTips.register({
		        target:  this,
		        title: '',
		        text: qt,
		        enabled: true,
		        showDelay: 20
	    	});
	    }
	})
});

// !Events Combobox
Pop.combo.Events = function(config) {
    config = config || {};
    Ext.applyIf(config, {
	    emptyText: _('pop.select...')
	    ,fieldLabel: _('pop.event')
	    ,typeAhead: true
	    ,valueField: 'id'
	    ,displayField: 'name'
	    ,fields: ['id','name']
	    ,url: Pop.config.connectorUrl
	    ,baseParams: {
	        action: 'mgr/events/popEventGetList'
	        ,active: 1
	    }
    });
    Pop.combo.Events.superclass.constructor.call(this, config);
};

Ext.extend(Pop.combo.Events, MODx.combo.ComboBox);
Ext.reg('pop-combo-events', Pop.combo.Events);

// !Event Groups Combobox
Pop.combo.EventGroups = function(config) {
    config = config || {};
    Ext.applyIf(config, {
	    emptyText: _('pop.select...')
	    ,fieldLabel: _('pop.event_group')
	    ,typeAhead: true
	    ,allowBlank: true
	    ,emptyText: ''
	    ,valueField: 'id'
	    ,displayField: 'name'
	    ,fields: ['id','name']
	    ,url: Pop.config.connectorUrl
	    ,baseParams: {
	        action: 'mgr/events/popEventGroupGetList'
	        ,active: 1
	    }
    });
    Pop.combo.EventGroups.superclass.constructor.call(this, config);
};

Ext.extend(Pop.combo.EventGroups, MODx.combo.ComboBox);
Ext.reg('pop-combo-event-groups', Pop.combo.EventGroups);