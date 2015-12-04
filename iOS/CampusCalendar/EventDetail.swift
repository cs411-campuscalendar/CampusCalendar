//
//  EventDetail.swift
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

class EventDetail: UIViewController, UITextFieldDelegate, UITextViewDelegate {
    var event: Event = Event()
    @IBOutlet weak var eventNameField: UITextField!
    @IBOutlet weak var eventDatePicker: UIDatePicker!
    @IBOutlet weak var eventLocationField: UITextField!
    @IBOutlet weak var eventDescriptionTextView: UITextView!
    @IBOutlet weak var scrollView: UIScrollView!
    
    @IBOutlet weak var attendButton: UIButton!
   
    

    override func viewDidLoad() {
        super.viewDidLoad()
        setupView()
        setupNavBar()
        registerForKeyboardNotifications()
        setAttendance()
    }
    
    func setupView(){
        self.navigationItem.title = self.event.name
        self.eventNameField.text = self.event.name
        self.eventDescriptionTextView.text = self.event.eventDescription
        self.eventLocationField.text = self.event.location
        
        let dateFormatter = NSDateFormatter()
        dateFormatter.dateFormat = "yyyy-MM-dd"
        let date = dateFormatter.dateFromString(self.event.eventDate)
        self.eventDatePicker.setDate(date!, animated:true)
        
        self.eventNameField.delegate = self
        self.eventNameField.returnKeyType = UIReturnKeyType.Done

        self.eventLocationField.delegate = self
        self.eventLocationField.returnKeyType = UIReturnKeyType.Done

        self.eventDescriptionTextView.delegate = self
        self.eventDescriptionTextView.returnKeyType = UIReturnKeyType.Done
    }
    
    func setupNavBar(){
        let saveButton = UIBarButtonItem(title: "Save", style: .Done, target: self, action: "didTapSaveButton")
        self.navigationItem.rightBarButtonItem = saveButton
    }
    
    func didTapSaveButton(){
        self.event.name = self.eventNameField.text!
        self.event.eventDescription = self.eventDescriptionTextView.text!
        self.event.location = self.eventLocationField.text!
        
        let dateFormatter = NSDateFormatter()
        dateFormatter.dateFormat = "yyyy-MM-dd"
        let newDate: NSDate = self.eventDatePicker.date
        self.event.eventDate = dateFormatter.stringFromDate(newDate)
        
        ServiceCall.updateEvent(self.event, completion:{()in
            self.navigationController?.popToRootViewControllerAnimated(true)
        })
    }
    
    func setAttendance() {
        let graphRequest : FBSDKGraphRequest = FBSDKGraphRequest(graphPath: "me?fields=id", parameters: nil)
        graphRequest.startWithCompletionHandler({ (connection, result, error) -> Void in
            if ((error) != nil) {
                let alert = UIAlertController(title:"Facebook graph SDK error", message:"Please try again" + "\n" + error.description, preferredStyle:UIAlertControllerStyle.Alert)
                alert.addAction(UIAlertAction(title:"Ok", style:UIAlertActionStyle.Default, handler: nil))
                self.presentViewController(alert, animated:true, completion:nil)
            } else {
                let fbId = result.valueForKey("id") as! String
            
                ServiceCall.isAttendingEvent(fbId, eventID:self.event.id, completion: {(isAttending: Bool) -> Void in
                    self.event.isAttending = isAttending
                    
                    if(self.event.isAttending) {
                        self.attendButton.setTitle("Do not attend", forState:UIControlState.Normal)
                    } else {
                        self.attendButton.setTitle("Attend", forState:UIControlState.Normal)
                    }
                })
            }
        })
    }
    
    // Keyboard handling
    
    func registerForKeyboardNotifications() {
        NSNotificationCenter.defaultCenter().addObserver(self, selector:"keyboardWasShown:", name:UIKeyboardDidShowNotification, object:nil)
        NSNotificationCenter.defaultCenter().addObserver(self, selector:"keyboardWillBeHidden:", name:UIKeyboardDidHideNotification, object:nil)
    }
    
    func keyboardWasShown(notification: NSNotification) {
        if(self.eventDescriptionTextView.isFirstResponder()){
            var contentInset:UIEdgeInsets = self.scrollView.contentInset
            contentInset.top = (-1) * 360
            self.scrollView.contentInset = contentInset
        }
    }
    
    func keyboardWillBeHidden(aNotification: NSNotification) {
        let contentInsets: UIEdgeInsets = UIEdgeInsetsZero;
        scrollView.contentInset = contentInsets;
        scrollView.scrollIndicatorInsets = contentInsets;
    }
    
    // UITextfield handling
    
    func textView(textView: UITextView, shouldChangeTextInRange range: NSRange, replacementText text: String) -> Bool {
        if(text == "\n") {
            textView.resignFirstResponder()
            return false
        }
        return true
    }
    
    func textFieldShouldReturn(textField: UITextField!) -> Bool {
        textField.resignFirstResponder()
        return true
    }
    
    // IBAction
    
    @IBAction func didTapAttendButton(sender: AnyObject) {
        let graphRequest : FBSDKGraphRequest = FBSDKGraphRequest(graphPath: "me?fields=id", parameters: nil)
        graphRequest.startWithCompletionHandler({ (connection, result, error) -> Void in
            if ((error) != nil) {
                let alert = UIAlertController(title:"Facebook graph SDK error", message:"Please try again" + "\n" + error.description, preferredStyle:UIAlertControllerStyle.Alert)
                alert.addAction(UIAlertAction(title:"Ok", style:UIAlertActionStyle.Default, handler: nil))
                self.presentViewController(alert, animated:true, completion:nil)
            } else {
                let fbId = result.valueForKey("id") as! String
                
                if(self.event.isAttending) {
                    ServiceCall.unattendEvent(fbId, eventID: self.event.id, completion:{(Void) -> Void in
                        self.event.isAttending = !self.event.isAttending
                        
                        if(self.event.isAttending) {
                            self.attendButton.setTitle("Do not attend", forState:UIControlState.Normal)
                        } else {
                            self.attendButton.setTitle("Attend", forState:UIControlState.Normal)
                        }
                    })
                } else {
                    ServiceCall.attendEvent(fbId, eventID: self.event.id, completion:{(Void) -> Void in
                        self.event.isAttending = !self.event.isAttending
                        
                        if(self.event.isAttending) {
                            self.attendButton.setTitle("Do not attend", forState:UIControlState.Normal)
                        } else {
                            self.attendButton.setTitle("Attend", forState:UIControlState.Normal)
                        }
                    })
                }
            }
        })
    }
}
