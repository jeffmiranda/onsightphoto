// !Event Groups Container
// !Journal Container
Pop.container.EventGroups = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'pop-cont-event-groups'
        ,defaults: { autoHeight: true }
        ,items: [{
	    	layout: 'form'
	    	,items: [{
		        xtype: 'pop-combo-events'
		        ,id: 'event-groups-events-combo'
		        ,fieldLabel: _('pop.event')
		        ,anchor: '95%'
		        ,listeners: {
			        select: {
				        fn: this.loadTree
				        ,scope: this
			        }
		        }
	        },{
		        xtype: 'pop-tree-event-groups'
                ,cls: 'main-wrapper'
                ,id: 'pop-tree-event-groups'
	        }]
        }]
    });
    Pop.container.EventGroups.superclass.constructor.call(this,config)
};
Ext.extend(Pop.container.EventGroups,Ext.Container,{
    // Load the event groups in the tree
	loadTree: function(combo, value) {
		var tree = Ext.getCmp('pop-tree-event-groups');
		tree.getLoader().baseParams = {
			event_id: value.id
		}
		tree.setTitle(value.data.name);
		tree.refresh();
		//tree.getRootNode().reload();
		//console.log(tree.getRootNode());
		//tree.getRootNode().setId(value.id);
		//tree.getRootNode().reload();
		//tree.getLoader().load(tree.getRootNode());
/*
		// reset the journal grid
		var gridJournal = Ext.getCmp('studentcentre-grid-journal');
		gridJournal.getStore().baseParams = {
            action: 'mgr/attendance/scJournalGetList'
		};
        Ext.getCmp('attendance-search-filter').reset();
        gridJournal.getBottomToolbar().changePage(1);
        gridJournal.refresh();
        
        // Get the Sched Class combobox
		var cbScheduledClass = Ext.getCmp('attendance-combo-scheduled-class-journal');
        if (cbScheduledClass) { // if the combobox was retrieved
        	cbScheduledClass.setDisabled(false); // enable the combobox
            var s = cbScheduledClass.store; // get the store
            s.baseParams['location_id'] = value.id; // set the location_id param
            s.baseParams['activeOnly'] = 1; // only get the active ones
            s.removeAll(); // removes any existing records from the store
            s.load(); // load the store with data
            cbScheduledClass.clearValue(); // clear the text value
        }
*/
    }
/*
    ,updateJournalGrid: function(combo, value) {
		var gridJournal = Ext.getCmp('studentcentre-grid-journal');
		gridJournal.getStore().baseParams = {
            action: 'mgr/attendance/scJournalGetList'
			,schedClassId: value.id
		};
        Ext.getCmp('attendance-search-filter').reset();
        gridJournal.getBottomToolbar().changePage(1);
        gridJournal.refresh();
    }
*/
});
Ext.reg('pop-cont-event-groups',Pop.container.EventGroups);


// !Event Groups Tree
Pop.tree.EventGroups = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        rootVisible: false
        ,enableDD:true
        ,header: true
        ,url: Pop.config.connectorUrl
        ,action: 'mgr/events/popEventGroupGetNodes'
        ,sortAction: 'mgr/events/popEventGroupSortNodes'
        ,useDefaultToolbar: true
        ,ddAppendOnly: false
        //,rootVisible: true
/*
        ,baseParams: {
            event_id: 2
        }
*/
        //,rootName: ''
        //,rootId: '/'
        
        //,collapsed: false
        ,stateful: true
        //,primaryKey: 'id'
        //,autoExpandRoot: true
        ,tbar: []
        //     ,tbarCfg: {
        //         id: config.id+'-tbar'
        //    }
        ,listeners: {
	        'load': function(n) {
		        if (n.attributes.active == 0) {
			        //n.getUI().addClass('tree-inactive');
			        n.setCls('tree-inactive');
		        }
		        n.eachChild(function(node) {
			        if (node.attributes.active == 0) {
				        node.setCls('tree-inactive');
			        }
		        })
	        }
/*
            'beforeclick': function(itm,e) {
            
            }
            ,'checkchange': function(node,checked){
                // check parent node (group) if child (category) is checked
                if(checked){
                    pn = node.parentNode;
                    pn.getUI().toggleCheck(checked);
                    // uncheck all child (category) nodes if parent (group) is unchecked
                }else{
                    node.eachChild(function(n) {
                        n.getUI().toggleCheck(checked);
                    });
                }
            }
*/
        }

    });
    Pop.tree.EventGroups.superclass.constructor.call(this,config)

};
Ext.extend(Pop.tree.EventGroups,MODx.tree.Tree,{
/*
    forms: {}
    ,windows: {}
    ,stores: {}

    ,_initExpand: function() {
    }
*/
    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} n The current node
     * @param {Ext.EventObject} e The event object run.
     */
    _showContextMenu: function(n,e) {
	    //console.log(n);
	    /**
		 * Note that this.cm is the context menu that comes
		 * with all MODExt trees
		 */
        this.cm.activeNode = n;
		var ui = n.getUI();
        this.cm.removeAll();
        var m = [];
        m.push({
            text: _('pop.create_event_group')
            ,handler: this.createEventGroup
        },{
	        text: _('pop.update_event_group')
            ,handler: this.updateEventGroup
        },{
	        text: _('pop.remove_event_group')
            ,handler: this.removeEventGroup
        },'-');
        /**
	     * Determine if the event group is published or unpublished
	     * and add the correct menu item
	     */
        if (ui.hasClass('tree-inactive')) {
            m.push({
                text: _('pop.activate')
                ,handler: this.toggleActive
            });
        } else {
            m.push({
                text: _('pop.deactivate')
                ,handler: this.toggleActive
            });
        }
        this.addContextMenuItem(m);
        this.cm.showAt(e.xy);
        e.stopEvent();
    }
    /**
     * Handles all drag events into the tree.
     * @param {Object} dropEvent The node dropped on the parent node.
     */
    ,_handleDrop: function(e) {
	    //console.log(e);
	    var point = e.point;
	    var dropNode = e.dropNode;
        var targetNode = e.target;
        var parentNode = targetNode.parentNode;
        
        // Don't allow the node to be dropped onto a parent that contains a child with the same name
        if ((point == 'append') && (targetNode.findChild('name', dropNode.attributes.name) !== null)) {
	        return false;
        }
        
        var n = parentNode.findChild('name', dropNode.attributes.name);
        
        // Don't allow the node to be dropped inside of a parent that contains a child with the same name
        if (n !== null) {
	        // Unless it's the parent that already contains the node to be dropped
	        if (n.attributes.id == dropNode.attributes.id) {
		        return true;
	        } else {
		        return false;
	        }
	        
        }

		return true;
    }
    ,_handleDrag: function(dropEvent) {
	    
        function simplifyNodes(node) {
            var resultNode = {};
            var kids = node.childNodes;
            var len = kids.length;
            for (var i = 0; i < len; i++) {
                resultNode[kids[i].id] = simplifyNodes(kids[i]);
            }
            return resultNode;
        }

        var encNodes = Ext.encode(simplifyNodes(dropEvent.tree.root));
        this.fireEvent('beforeSort',encNodes);
        
        var eventId = Ext.getCmp('event-groups-events-combo').getValue();
        
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                event: eventId
                ,target: dropEvent.target.attributes.id
                ,source: dropEvent.source.dragData.node.attributes.id
                ,point: dropEvent.point
                ,data: encodeURIComponent(encNodes)
                ,action: this.config.sortAction || 'sort'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var el = dropEvent.dropNode.getUI().getTextEl();
                    if (el) {Ext.get(el).frame();}
                    this.fireEvent('afterSort',{event:dropEvent,result:r});
                },scope:this}
                ,'failure': {fn:function(r) {
                    MODx.form.Handler.errorJSON(r);
                    this.refresh();
                    return false;
                },scope:this}
            }
        });
    }
    ,createEventGroup: function(n,e) {
	    var r = {};
	    if (this.cm.activeNode.attributes) {
		    r['event_id'] = this.cm.activeNode.attributes.event_id;
		    r['parent'] = this.cm.activeNode.attributes.parent;
	    }
	    
	    var w = MODx.load({
            xtype: 'pop-window-event-group-create'
            ,record: r
            ,listeners: {
                'success': {fn:function() {
                    var node = (this.cm.activeNode.attributes.parent) ? this.cm.activeNode.attributes.parent : 'root';
                    this.refreshNode(node,true);
                },scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }
    ,updateEventGroup: function(itm,e) {
        var r = this.cm.activeNode.attributes;
        var w = MODx.load({
            xtype: 'pop-window-event-group-update'
            ,record: r
            ,listeners: {
                'success':{fn:function(r) {
	                /**
		             * I'm sure there's a better way to reload the nodes
		             * other than reloading the whole fucking tree, but
		             * I've spent enough goddam time on this issue and
		             * I still can't figure it out. So fuck it!
		             */
/*
	                var n = this.cm.activeNode;
	                var ui = n.getUI();
	                if (r.a.result.object.active == 0) {
		                ui.addClass('tree-inactive');
	                } else {
		                ui.removeClass('tree-inactive');
	                }
					var nodeId = (n.attributes.id) ? n.attributes.id : 'root';
                    this.refreshNode(nodeId);
*/
                    this.refresh();
                },scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.setValues(r);
        w.show(e.target);
    }
    ,removeEventGroup: function(itm,e) {
        var id = this.cm.activeNode.attributes.id;
        MODx.msg.confirm({
            title: _('pop.warning')
            ,text: _('pop.event_group_conf_del')
            ,url: Pop.config.connectorUrl
            ,params: {
                action: 'mgr/events/popEventGroupRemove'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function() {
                    this.cm.activeNode.remove();
                },scope:this}
            }
        });
    }
    ,toggleActive: function(itm,e) {
	    var n = this.cm.activeNode;
	    var na = n.attributes;
	    Ext.Ajax.request({
		   url: Pop.config.connectorUrl
		   ,params: {
		        action: 'mgr/events/popEventGroupUpdate'
		        ,id: na.id
		        ,toggleActive: true
		    }
		   ,success: function(r, opts) {
                var tree = Ext.getCmp('pop-tree-event-groups');
                tree.refresh();
		   }
		   ,failure: function(r, opts) {
		      Ext.MessageBox.alert(_('pop.err'), r.status);
		      console.log('Ajax server-side failure with status code ' + r.status);
		   }
		});
    }

});
Ext.reg('pop-tree-event-groups',Pop.tree.EventGroups);


// Create new event group window
Pop.window.CreateEventGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('pop.create_event_group')
        ,url: Pop.config.connectorUrl
        ,action: 'mgr/events/popEventGroupCreate'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'event_id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('pop.event_group')
            ,name: 'name'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            fieldLabel: _('pop.parent')
            ,name: 'parent'
            ,hiddenName: 'parent'
            ,id: 'pop-create-event-group-combo-event-groups'
            ,xtype: 'pop-combo-event-groups'
            ,anchor: '100%'
            ,listeners: {
		        'render': {fn: function() {
					var cb = Ext.getCmp('pop-create-event-group-combo-event-groups');
					cb.baseParams.event_id = config.record.event_id;
		        },scope:this}
		    }
        },{
            fieldLabel: _('pop.order')
            ,name: 'rank'
            ,xtype: 'numberfield'
            ,anchor: '100%'
        }]
    });
    Pop.window.CreateEventGroup.superclass.constructor.call(this,config);
};
Ext.extend(Pop.window.CreateEventGroup,MODx.Window);
Ext.reg('pop-window-event-group-create',Pop.window.CreateEventGroup);


// Update event group window
Pop.window.UpdateEventGroup = function(config) {
    config = config || {};
    //console.log(config.record);
    Ext.applyIf(config,{
        title: _('pop.update_event_group')
        ,url: Pop.config.connectorUrl
        ,action: 'mgr/events/popEventGroupUpdate'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'hidden'
            ,name: 'event_id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('pop.event_group')
            ,name: 'name'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: 'xcheckbox'
            ,fieldLabel: _('pop.active')
            ,name: 'active'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            fieldLabel: _('pop.parent')
            ,name: 'parent'
            ,hiddenName: 'parent'
            ,id: 'pop-update-event-group-combo-event-groups'
            ,xtype: 'pop-combo-event-groups'
            ,anchor: '100%'
            ,listeners: {
		        'render': {fn: function() {
					var cb = Ext.getCmp('pop-update-event-group-combo-event-groups');
					cb.baseParams.event_id = config.record.event_id;
		        },scope:this}
		    }
        },{
            fieldLabel: _('pop.order')
            ,name: 'rank'
            ,xtype: 'numberfield'
            ,anchor: '100%'
            ,value: 0
        }]
    });
    Pop.window.UpdateEventGroup.superclass.constructor.call(this,config);
};
Ext.extend(Pop.window.UpdateEventGroup,MODx.Window);
Ext.reg('pop-window-event-group-update',Pop.window.UpdateEventGroup);