//
//  EventsViewController.swift
//  CampusCalendar
//
//  Created by James Wegner on 10/17/15.
//  Copyright © 2015 James Wegner. All rights reserved.
//

import UIKit
import FBAudienceNetwork
import FBSDKCoreKit
import FBSDKLoginKit
import FBSDKMessengerShareKit
import FBSDKShareKit

enum EventFilter {
    case Recommended
    case Sports
    case Academic
}

class EventsViewController: UIViewController, UITableViewDelegate, UITableViewDataSource {
    @IBOutlet weak var tableView: UITableView!
    @IBOutlet weak var editButton: UIBarButtonItem!

    var events: NSMutableArray = []
    var searchResults: NSMutableArray = []
    var refreshControl: UIRefreshControl = UIRefreshControl()
    var eventFilter: EventFilter = EventFilter.Recommended

    override func viewDidLoad() {
        super.viewDidLoad()
        setupView()
        setupTableView()
        
        // Check FB Login
        
        if(FBSDKAccessToken.currentAccessToken() != nil) {
            
            
        } else {
            //They need to log in
            self.performSegueWithIdentifier("loginView", sender:nil)
        }
    }
    
    override func viewDidAppear(animated: Bool) {
        super.viewDidAppear(animated)
        getEvents()
    }
    
    func setupView() {
        self.navigationItem.title = "Campus Calendar"
    }
    
    func setupTableView() {
        self.tableView.tableFooterView = UIView.init(frame: CGRect(x: 0,y: 0,width: 0,height: 0))
        self.tableView.tableHeaderView = UIView.init(frame: CGRect(x: 0,y: 0,width: 0,height: 0))
        self.tableView.delegate = self
        self.tableView.dataSource = self
        self.tableView.registerNib(UINib(nibName:"EventTableViewCell", bundle:NSBundle.mainBundle()), forCellReuseIdentifier:"eventCell")
        
        refreshControl.attributedTitle = NSAttributedString(string:"Refreshing...")
        refreshControl.addTarget(self, action:"getEvents", forControlEvents:.ValueChanged)
        self.tableView.addSubview(refreshControl)

        self.searchDisplayController?.searchResultsTableView.tableFooterView = UIView.init(frame: CGRect(x: 0,y: 0,width: 0,height: 0))
        self.searchDisplayController?.searchResultsTableView.tableHeaderView = UIView.init(frame: CGRect(x: 0,y: 0,width: 0,height: 0))
        self.searchDisplayController?.searchResultsTableView.delegate = self
        self.searchDisplayController?.searchResultsTableView.dataSource = self
        self.searchDisplayController?.searchResultsTableView.registerNib(UINib(nibName:"EventTableViewCell", bundle:NSBundle.mainBundle()), forCellReuseIdentifier:"eventCell")
    }
    
    func getEvents(){
        /*ServiceCall.getAllEvents({(result) in
            self.events = result
            self.tableView .reloadData()
            self.refreshControl.endRefreshing()
        })*/
        
        switch eventFilter {
        case .Recommended:
            ServiceCall.getRecommendedEvents({(result) in
                self.events = result
                self.tableView .reloadData()
                self.refreshControl.endRefreshing()
            });
            break
        case .Sports:
            ServiceCall.getSportEvents({(result) in
                self.events = result
                self.tableView .reloadData()
                self.refreshControl.endRefreshing()
            });
            break
        case .Academic:
            ServiceCall.getAcademicEvents({(result) in
                self.events = result
                self.tableView .reloadData()
                self.refreshControl.endRefreshing()
            });
            break
        }
    }
    
    // UITableViewDelegate
    
    func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath: NSIndexPath) -> UITableViewCell {
        var eventCell : EventTableViewCell?
        eventCell = tableView.dequeueReusableCellWithIdentifier("eventCell") as? EventTableViewCell
        
        var currEvent = Event()
        if(tableView == self.tableView){
            currEvent = self.events.objectAtIndex(indexPath.row) as! Event

        }else if(tableView == self.searchDisplayController?.searchResultsTableView){
            currEvent = self.searchResults.objectAtIndex(indexPath.row) as! Event
        }
        
        eventCell?.eventLabel.text = currEvent.name
        eventCell?.eventDateLabel.text = currEvent.eventDate
        
        return eventCell!
    }
    
    func tableView(tableView: UITableView, didSelectRowAtIndexPath indexPath: NSIndexPath) {
        var currEvent = Event()
        
        if(tableView == self.tableView){
            currEvent = self.events.objectAtIndex(indexPath.row) as! Event
            tableView.deselectRowAtIndexPath(indexPath, animated: true)
            
        }else if(tableView == self.searchDisplayController?.searchResultsTableView){
            currEvent = self.searchResults.objectAtIndex(indexPath.row) as! Event
            self.searchDisplayController?.searchResultsTableView.deselectRowAtIndexPath(indexPath, animated: true)
        }
        
        self.performSegueWithIdentifier("eventDetail", sender:currEvent)
    }
    
    func tableView(tableView: UITableView, heightForRowAtIndexPath indexPath: NSIndexPath) -> CGFloat {
        return 78.0
    }
    
    func tableView(tableView: UITableView, commitEditingStyle editingStyle: UITableViewCellEditingStyle, forRowAtIndexPath indexPath: NSIndexPath) {
        if editingStyle == UITableViewCellEditingStyle.Delete {
            ServiceCall.deleteEvent(events.objectAtIndex(indexPath.row) as! Event, completion:{()
                })
            events.removeObjectAtIndex(indexPath.row)
            tableView.deleteRowsAtIndexPaths([indexPath], withRowAnimation: UITableViewRowAnimation.Automatic)
        }
    }
    
    func tableView(tableView: UITableView, canEditRowAtIndexPath indexPath: NSIndexPath) -> Bool {
        return true;
    }
    
    // UITableViewDatasSource
    
    func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        if(tableView == self.tableView){
            return events.count

        }else if(tableView == self.searchDisplayController?.searchResultsTableView){
            return searchResults.count
            
        }else{
            return 0
        }
    }
    
    // Segue
    
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        if(segue.identifier == "eventDetail"){
            let controller: EventDetail = segue.destinationViewController as! EventDetail
            controller.event = sender as! Event
        }
    }
    
    // Search
    
    func filterContentForSearchText(searchText: String) {
        ServiceCall.searchDatabase(searchText, completion:{(events) in
            self.searchResults = events
            self.searchDisplayController?.searchResultsTableView.reloadData()
        })
    }
    
    func searchDisplayController(controller: UISearchDisplayController!, shouldReloadTableForSearchString searchString: String!) -> Bool {
        self.filterContentForSearchText(searchString)
        return true
    }
    
    func searchDisplayController(controller: UISearchDisplayController!, shouldReloadTableForSearchScope searchOption: Int) -> Bool {
        self.filterContentForSearchText(self.searchDisplayController!.searchBar.text!)
        return true
    }
    
    // IBAction
    
    @IBAction func didTapAddEventButton(sender: AnyObject) {
        self.performSegueWithIdentifier("addEvent", sender:nil)
    }
    
    @IBAction func didTapEditButton(sender: AnyObject) {
        let optionMenu = UIAlertController(title:"Event Filters", message: nil, preferredStyle: .ActionSheet)
        
        let recommended = UIAlertAction(title: "Recommended", style: .Default, handler: {
            (alert: UIAlertAction!) -> Void in
            self.eventFilter = EventFilter.Recommended
            self.getEvents()
        })
        
        let sports = UIAlertAction(title: "Sports", style: .Default, handler: {
            (alert: UIAlertAction!) -> Void in
            self.eventFilter = EventFilter.Sports
            self.getEvents()
        })
        
        let academic = UIAlertAction(title: "Academic", style: .Default, handler: {
            (alert: UIAlertAction!) -> Void in
            self.eventFilter = EventFilter.Academic
            self.getEvents()
        })
        
        optionMenu.addAction(recommended)
        optionMenu.addAction(sports)
        optionMenu.addAction(academic)
        
        self.presentViewController(optionMenu, animated: true, completion: nil)
        
        /*if(self.tableView.editing){
            self.editButton.title = "Edit"
            self.tableView.setEditing(false, animated:true)
        }else{
            self.editButton.title = "Done"
            self.tableView.setEditing(true, animated:true)
        }*/
    }
}
