//
//  ServiceCall.swift
//  CampusCalendar
//
//  Created by James Wegner on 10/17/15.
//  Copyright © 2015 James Wegner. All rights reserved.
//

import UIKit

class ServiceCall: NSObject {
    
    // Get all events
    
    static func getAllEvents(completion: (result: NSMutableArray) -> Void) {
        let urlPath: String = "http://campuscalendar.web.engr.illinois.edu/events.php"
        let url: NSURL = NSURL(string: urlPath)!
        let request: NSURLRequest = NSURLRequest(URL: url)
        let config = NSURLSessionConfiguration.defaultSessionConfiguration()
        let session = NSURLSession(configuration: config)
        
        let task : NSURLSessionDataTask = session.dataTaskWithRequest(request, completionHandler: {(data, response, error) in
            if((error) != nil){
                print(response)
                print("Error getting events - " + error!.description)
                
            }else{
                var events: NSMutableArray = []
                do {
                    let jsonEvents = try NSJSONSerialization.JSONObjectWithData(data!, options:NSJSONReadingOptions.AllowFragments) as! NSArray
                    events = ServiceCall.parseGetEvents(jsonEvents)
                    
                } catch {
                    print("json error: (error)")
                }
                
                dispatch_async(dispatch_get_main_queue(),{
                    print("get events")
                    completion(result: events)
                })
            }
        });
        task.resume()
    }
    
    static func parseGetEvents(jsonEvents:NSArray) -> NSMutableArray {
        let events: NSMutableArray = NSMutableArray()
       
        for jsonEvent in jsonEvents {
            let id: String = jsonEvent["id"] as! String
            let name: String = jsonEvent["name"] as! String
            let location: String = jsonEvent["location"] as! String
            let eventDescription: String = jsonEvent["description"] as! String
            let eventDate: String = jsonEvent["date"] as! String
            
            let event: Event = Event()
            event.id = id
            event.name = name
            event.location = location
            event.eventDescription = eventDescription
            event.eventDate = eventDate
            events.addObject(event)
        }
        
        return events
    }
    
    // Get recommended events
    
    static func getRecommendedEvents(completion: (result: NSMutableArray) -> Void) {
        let urlPath: String = "http://campuscalendar.web.engr.illinois.edu/events_recommended.php"
        let url: NSURL = NSURL(string: urlPath)!
        let request: NSURLRequest = NSURLRequest(URL: url)
        let config = NSURLSessionConfiguration.defaultSessionConfiguration()
        let session = NSURLSession(configuration: config)
        
        let task : NSURLSessionDataTask = session.dataTaskWithRequest(request, completionHandler: {(data, response, error) in
            if((error) != nil){
                print(response)
                print("Error getting events - " + error!.description)
                
            }else{
                var events: NSMutableArray = []
                do {
                    let jsonEvents = try NSJSONSerialization.JSONObjectWithData(data!, options:NSJSONReadingOptions.AllowFragments) as! NSArray
                    events = ServiceCall.parseGetEvents(jsonEvents)
                    
                } catch {
                    print("json error: (error)")
                }
                
                dispatch_async(dispatch_get_main_queue(),{
                    print("get events")
                    completion(result: events)
                })
            }
        });
        task.resume()
    }
    
    // Get sport events
    
    static func getSportEvents(completion: (result: NSMutableArray) -> Void) {
        let urlPath: String = "http://campuscalendar.web.engr.illinois.edu/events_sports.php"
        let url: NSURL = NSURL(string: urlPath)!
        let request: NSURLRequest = NSURLRequest(URL: url)
        let config = NSURLSessionConfiguration.defaultSessionConfiguration()
        let session = NSURLSession(configuration: config)
        
        let task : NSURLSessionDataTask = session.dataTaskWithRequest(request, completionHandler: {(data, response, error) in
            if((error) != nil){
                print(response)
                print("Error getting events - " + error!.description)
                
            }else{
                var events: NSMutableArray = []
                do {
                    let jsonEvents = try NSJSONSerialization.JSONObjectWithData(data!, options:NSJSONReadingOptions.AllowFragments) as! NSArray
                    events = ServiceCall.parseGetEvents(jsonEvents)
                    
                } catch {
                    print("json error: (error)")
                }
                
                dispatch_async(dispatch_get_main_queue(),{
                    print("get events")
                    completion(result: events)
                })
            }
        });
        task.resume()
    }
    
    // Get academic events
    
    static func getAcademicEvents(completion: (result: NSMutableArray) -> Void) {
        let urlPath: String = "http://campuscalendar.web.engr.illinois.edu/events_academic.php"
        let url: NSURL = NSURL(string: urlPath)!
        let request: NSURLRequest = NSURLRequest(URL: url)
        let config = NSURLSessionConfiguration.defaultSessionConfiguration()
        let session = NSURLSession(configuration: config)
        
        let task : NSURLSessionDataTask = session.dataTaskWithRequest(request, completionHandler: {(data, response, error) in
            if((error) != nil){
                print(response)
                print("Error getting events - " + error!.description)
                
            }else{
                var events: NSMutableArray = []
                do {
                    let jsonEvents = try NSJSONSerialization.JSONObjectWithData(data!, options:NSJSONReadingOptions.AllowFragments) as! NSArray
                    events = ServiceCall.parseGetEvents(jsonEvents)
                    
                } catch {
                    print("json error: (error)")
                }
                
                dispatch_async(dispatch_get_main_queue(),{
                    print("get events")
                    completion(result: events)
                })
            }
        });
        task.resume()
    }
    
    // Update event

    static func updateEvent(event:Event, completion:() -> Void) {
        var urlPath: String = "http://campuscalendar.web.engr.illinois.edu/update_event.php?id=" + event.id + "&description=" + event.eventDescription + "&location=" + event.location + "&date=" + event.eventDate + "&name=" + event.name
        urlPath = urlPath.stringByAddingPercentEscapesUsingEncoding(NSUTF8StringEncoding)!

        let url: NSURL = NSURL(string: urlPath)!
        let request: NSURLRequest = NSURLRequest(URL: url)
        let config = NSURLSessionConfiguration.defaultSessionConfiguration()
        let session = NSURLSession(configuration: config)
        
        let task : NSURLSessionDataTask = session.dataTaskWithRequest(request, completionHandler: {(data, response, error) in
            if((error) != nil){
                print(response)
                print("Error updating event - " + error!.description)
            }else{
                dispatch_async(dispatch_get_main_queue(),{
                    print("event updated")
                    completion()
                })
            }
        });
        task.resume()
    }
    
    // Add event
    
    static func addEvent(event:Event, completion:() -> Void) {
        var urlPath: String = "http://campuscalendar.web.engr.illinois.edu/add_event.php?id=" + event.id + "&description=" + event.eventDescription + "&location=" + event.location + "&date=" + event.eventDate + "&name=" + event.name
        urlPath = urlPath.stringByAddingPercentEscapesUsingEncoding(NSUTF8StringEncoding)!
        
        let url: NSURL = NSURL(string: urlPath)!
        let request: NSURLRequest = NSURLRequest(URL: url)
        let config = NSURLSessionConfiguration.defaultSessionConfiguration()
        let session = NSURLSession(configuration: config)
        
        let task : NSURLSessionDataTask = session.dataTaskWithRequest(request, completionHandler: {(data, response, error) in
            if((error) != nil){
                print(response)
                print("Error adding event - " + error!.description)
            }else{
                dispatch_async(dispatch_get_main_queue(),{
                    print("add event")
                    completion()
                })
            }
        });
        task.resume()
    }
    
    // Delete event
    
    static func deleteEvent(event:Event, completion:() -> Void) {
        var urlPath: String = "http://campuscalendar.web.engr.illinois.edu/delete_event.php?id=" + event.id
        urlPath = urlPath.stringByAddingPercentEscapesUsingEncoding(NSUTF8StringEncoding)!
        let url: NSURL = NSURL(string: urlPath)!
        let request: NSURLRequest = NSURLRequest(URL: url)
        let config = NSURLSessionConfiguration.defaultSessionConfiguration()
        let session = NSURLSession(configuration: config)
        
        let task : NSURLSessionDataTask = session.dataTaskWithRequest(request, completionHandler: {(data, response, error) in
            if((error) != nil){
                print(response)
                print("Error deleting event - " + error!.description)
            }else{
                dispatch_async(dispatch_get_main_queue(),{
                    print("event deleted")
                    completion()
                })
            }
        });
        task.resume()
    }
    
    // Search events
    
    static func searchDatabase(keywords: String, completion: (result: NSMutableArray) -> Void) {
        var urlPath: String = "http://campuscalendar.web.engr.illinois.edu/search/search.php?keywords=" + keywords
        urlPath = urlPath.stringByAddingPercentEscapesUsingEncoding(NSUTF8StringEncoding)!
        let url: NSURL = NSURL(string: urlPath)!
        let request: NSURLRequest = NSURLRequest(URL: url)
        let config = NSURLSessionConfiguration.defaultSessionConfiguration()
        let session = NSURLSession(configuration: config)
        
        let task : NSURLSessionDataTask = session.dataTaskWithRequest(request, completionHandler: {(data, response, error) in
            if((error) != nil){
                print(response)
                print("Error searching - " + error!.description)
            }else{
                
                var events: NSMutableArray = []

                do {
                    let jsonEvents = try NSJSONSerialization.JSONObjectWithData(data!, options:NSJSONReadingOptions.AllowFragments) as! NSArray
                    events = ServiceCall.parseGetEvents(jsonEvents)
                    
                } catch {
                    print("json error")
                }
                
                dispatch_async(dispatch_get_main_queue(),{
                    print("search: " + keywords)
                    completion(result: events)
                })
            }
        });
        task.resume()
    }
    
    // Register user
    
    static func registerUser(firstName: String, lastName: String, email: String, pictureURL: String, fbID: String, gender: String, address: String, university: String, completion: () -> Void) {
        
        var urlPath: String = "http://campuscalendar.web.engr.illinois.edu/add_user.php?picture=" +  pictureURL + "&first_name=" + firstName + "&university=" + university + "&address=" + address + "&gender=" + gender + "&facebook_id=" + fbID + "&email=" + email + "&last_name=" + lastName
        
        urlPath = urlPath.stringByAddingPercentEscapesUsingEncoding(NSUTF8StringEncoding)!
        let url: NSURL = NSURL(string: urlPath)!
        let request: NSURLRequest = NSURLRequest(URL: url)
        let config = NSURLSessionConfiguration.defaultSessionConfiguration()
        let session = NSURLSession(configuration: config)
        
        let task : NSURLSessionDataTask = session.dataTaskWithRequest(request, completionHandler: {(data, response, error) in
            if((error) != nil){
                print(response)
                print("Error resgistering user - " + error!.description)
            }else{
                dispatch_async(dispatch_get_main_queue(),{
                    print("user registered")
                    completion()
                })
            }
        });
        task.resume()
    }
    
    // Add friend
    
    static func addFriend(idA: String, idB: String, completion: () -> Void) {
        
        var urlPath: String = "http://campuscalendar.web.engr.illinois.edu/friends_with.php?id_a=" + idA + "&id_b=" + idB
        
        urlPath = urlPath.stringByAddingPercentEscapesUsingEncoding(NSUTF8StringEncoding)!
        let url: NSURL = NSURL(string: urlPath)!
        let request: NSURLRequest = NSURLRequest(URL: url)
        let config = NSURLSessionConfiguration.defaultSessionConfiguration()
        let session = NSURLSession(configuration: config)
        
        let task : NSURLSessionDataTask = session.dataTaskWithRequest(request, completionHandler: {(data, response, error) in
            if((error) != nil){
                print(response)
                print("Error adding friend - " + error!.description)
            }else{
                dispatch_async(dispatch_get_main_queue(),{
                    print("friend added")
                    completion()
                })
            }
        });
        task.resume()
    }
    
    // Attend Event
    
    static func attendEvent(fbID: String, eventID: String, completion: () -> Void) {
        
        var urlPath: String = "http://campuscalendar.web.engr.illinois.edu/attend_event.php?facebook_id=" + fbID + "&event_id=" + eventID
        
        urlPath = urlPath.stringByAddingPercentEscapesUsingEncoding(NSUTF8StringEncoding)!
        let url: NSURL = NSURL(string: urlPath)!
        let request: NSURLRequest = NSURLRequest(URL: url)
        let config = NSURLSessionConfiguration.defaultSessionConfiguration()
        let session = NSURLSession(configuration: config)
        
        let task : NSURLSessionDataTask = session.dataTaskWithRequest(request, completionHandler: {(data, response, error) in
            if((error) != nil){
                print(response)
                print("Error attending event - " + error!.description)
            }else{
                dispatch_async(dispatch_get_main_queue(),{
                    print("attendEvent attending event")
                    completion()
                })
            }
        });
        task.resume()
    }
    
    static func isAttendingEvent(fbID: String, eventID: String, completion: (isAttending: Bool) -> Void) {
        
        var urlPath: String = "http://campuscalendar.web.engr.illinois.edu/is_attending.php?facebook_id=" + fbID + "&event_id=" + eventID
        
        urlPath = urlPath.stringByAddingPercentEscapesUsingEncoding(NSUTF8StringEncoding)!
        let url: NSURL = NSURL(string: urlPath)!
        let request: NSURLRequest = NSURLRequest(URL: url)
        let config = NSURLSessionConfiguration.defaultSessionConfiguration()
        let session = NSURLSession(configuration: config)
        
        let task : NSURLSessionDataTask = session.dataTaskWithRequest(request, completionHandler: {(data, response, error) in
            if((error) != nil){
                print(response)
                print("Error attending event - " + error!.description)
            }else{
                let dataString = NSString(data: data!, encoding: NSUTF8StringEncoding)
                
                dispatch_async(dispatch_get_main_queue(),{
                    if(dataString == "false") {
                        print("user is not attending event")
                        completion(isAttending: false)
                    } else {
                        print("user is attending event")
                        completion(isAttending: true)
                    }
                })
            }
        });
        task.resume()
    }
    
    // Unattend event
    
    static func unattendEvent(fbID: String, eventID: String, completion: () -> Void) {
        
        var urlPath: String = "http://campuscalendar.web.engr.illinois.edu/unattend_event.php?facebook_id=" + fbID + "&event_id=" + eventID
        
        urlPath = urlPath.stringByAddingPercentEscapesUsingEncoding(NSUTF8StringEncoding)!
        let url: NSURL = NSURL(string: urlPath)!
        let request: NSURLRequest = NSURLRequest(URL: url)
        let config = NSURLSessionConfiguration.defaultSessionConfiguration()
        let session = NSURLSession(configuration: config)
        
        let task : NSURLSessionDataTask = session.dataTaskWithRequest(request, completionHandler: {(data, response, error) in
            if((error) != nil){
                print(response)
                print("Error unattending event - " + error!.description)
            }else{
                dispatch_async(dispatch_get_main_queue(),{
                    print("unattendEvent unattending event")
                    completion()
                })
            }
        });
        task.resume()
    }
}
