//
//  UserFacebookLoginViewController.swift
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

class UserFacebookLoginViewController: UIViewController, FBSDKLoginButtonDelegate {
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        self.navigationItem.title = "Campus Calendar Login"

        let loginButton = FBSDKLoginButton()
        loginButton.readPermissions = ["public_profile", "email", "user_friends"];
        loginButton.delegate = self
        loginButton.frame = CGRect(x: 0, y: 0, width: 250, height: 65)
        loginButton.center = self.view.center
        self.view.addSubview(loginButton)
    }
    
    func loginButton(loginButton: FBSDKLoginButton!, didCompleteWithResult result: FBSDKLoginManagerLoginResult!, error: NSError!) {
        if(error != nil) {
            let alert = UIAlertController(title:"Facebook login error", message:"Please try again" + "\n" + error.description, preferredStyle:UIAlertControllerStyle.Alert)
            alert.addAction(UIAlertAction(title:"Ok", style:UIAlertActionStyle.Default, handler: nil))
            self.presentViewController(alert, animated:true, completion:nil)
        } else {
            
            let graphRequest : FBSDKGraphRequest = FBSDKGraphRequest(graphPath: "me?fields=id,name,email,gender,picture,friends", parameters: nil)
            graphRequest.startWithCompletionHandler({ (connection, result, error) -> Void in
                if ((error) != nil) {
                    let alert = UIAlertController(title:"Facebook graph SDK error", message:"Please try again" + "\n" + error.description, preferredStyle:UIAlertControllerStyle.Alert)
                    alert.addAction(UIAlertAction(title:"Ok", style:UIAlertActionStyle.Default, handler: nil))
                    self.presentViewController(alert, animated:true, completion:nil)
                }
                else {
                    let fbId = result.valueForKey("id") as! String
                    let userName = result.valueForKey("name") as! String
                    let email = result.valueForKey("email") as! String
                    let gender = result.valueForKey("gender") as! String
                    
                    let fullNameArr = userName.componentsSeparatedByString(" ")
                    let firstName: String = fullNameArr[0]
                    let lastName: String = fullNameArr[1]

                    let friendObjects = result.valueForKey("friends") as! NSDictionary
                    let data = friendObjects.valueForKey("data") as! [NSDictionary]
                
                    ServiceCall.registerUser(firstName, lastName: lastName, email: email, pictureURL: "pic", fbID: fbId, gender: gender, address:"123 Fake St.", university: "UIUC", completion:{ () -> Void in
                        
                        for friendObject in data {
                            print(friendObject["id"] as! NSString)
                            
                            let friendFBId = friendObject.valueForKey("id") as! String
                            let friendUserName = friendObject.valueForKey("name") as! String
                            
                            ServiceCall.addFriend(fbId, idB: friendFBId, completion:{ () -> Void in
                            })
                        }
                    
                    })
                }
            })
        }
        
        self.dismissViewControllerAnimated(true, completion:nil)
    }
    
    func loginButtonWillLogin(loginButton: FBSDKLoginButton!) -> Bool {
        return true
    }
    
    func loginButtonDidLogOut(loginButton: FBSDKLoginButton!) {
    }
    
}
