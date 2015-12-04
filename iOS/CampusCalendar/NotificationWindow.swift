//
//  NotificationWindow.swift
//  CampusCalendar
//
//  Created by James Wegner on 10/18/15.
//  Copyright © 2015 James Wegner. All rights reserved.
//

import UIKit

class NotificationWindow: UIView {
    required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
    }
    
    override init(frame: CGRect) {
        let notificationFrame = CGRect(x:0, y:0, width: 50, height:50)
        super.init(frame: notificationFrame)
        self.backgroundColor = UIColor(red:0, green:0, blue:0, alpha: 0.6)
    }
    
    convenience init(){
        self.init(frame: CGRect())
    }
}