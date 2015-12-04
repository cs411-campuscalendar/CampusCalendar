//
//  Event.swift
//  CampusCalendar
//
//  Created by James Wegner on 10/17/15.
//  Copyright © 2015 James Wegner. All rights reserved.
//

import UIKit

class Event: NSObject {
    var id: String = ""
    var name: String = ""
    var eventDescription: String = ""
    var eventDate: String = ""
    var location: String = ""
    var isAttending: Bool = false
}
