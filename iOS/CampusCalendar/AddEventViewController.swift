//
//  AddEventViewController.swift
//  CampusCalendar
//
//  Created by James Wegner on 10/20/15.
//  Copyright © 2015 James Wegner. All rights reserved.
//

import UIKit

class AddEventViewController: UIViewController, UITextViewDelegate, UITextFieldDelegate {
    @IBOutlet weak var scrollView: UIScrollView!
    @IBOutlet weak var eventNameField: UITextField!
    @IBOutlet weak var eventLocationField: UITextField!
    @IBOutlet weak var eventDescriptionTextView: UITextView!
    @IBOutlet weak var eventDatePicker: UIDatePicker!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        setupView()
        registerForKeyboardNotifications()
    }
    
    func setupView(){
        self.navigationItem.title = "Add Event"
        self.eventDescriptionTextView.text = ""
        
        self.eventNameField.delegate = self
        self.eventNameField.returnKeyType = UIReturnKeyType.Done
        
        self.eventLocationField.delegate = self
        self.eventLocationField.returnKeyType = UIReturnKeyType.Done
        
        self.eventDescriptionTextView.delegate = self
        self.eventDescriptionTextView.returnKeyType = UIReturnKeyType.Done
    }
    
    // IBAction
    
    @IBAction func didTapCancel(sender: AnyObject) {
        self.dismissViewControllerAnimated(true, completion:nil)
    }

    @IBAction func didTapSaveEvent(sender: AnyObject) {
        let event: Event = Event()
        event.name = self.eventNameField.text!
        event.eventDescription = self.eventDescriptionTextView.text!
        event.location = self.eventLocationField.text!
        
        let dateFormatter = NSDateFormatter()
        dateFormatter.dateFormat = "yyyy-MM-dd"
        let newDate: NSDate = self.eventDatePicker.date
        event.eventDate = dateFormatter.stringFromDate(newDate)
        ServiceCall.addEvent(event, completion:{()in
            self.dismissViewControllerAnimated(true, completion:nil)
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
}
