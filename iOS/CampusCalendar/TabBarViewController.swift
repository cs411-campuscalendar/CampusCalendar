//
//  TabBarViewController.swift
//  CampusCalendar
//
//  Created by James Wegner on 12/2/15.
//  Copyright © 2015 James Wegner. All rights reserved.
//

import UIKit
import FBAudienceNetwork
import FBSDKCoreKit
import FBSDKLoginKit
import FBSDKMessengerShareKit
import FBSDKShareKit

class TabBarViewController: UITabBarController {

    override func viewDidLoad() {
        super.viewDidLoad()
        
        let tabItems = self.tabBar.items! as [UITabBarItem]
        let tabItem0 = tabItems[0] as UITabBarItem
        let tabItem1 = tabItems[1] as UITabBarItem
        let tabItem2 = tabItems[2] as UITabBarItem
        tabItem0.title = "Recommended"
        tabItem1.title = "Sports"
        tabItem2.title = "Academic"
    }
}
