//
//  EventTableViewCell.swift
//  CampusCalendar
//
//  Created by James Wegner on 10/17/15.
//  Copyright © 2015 James Wegner. All rights reserved.
//

import UIKit

class EventTableViewCell: UITableViewCell {
    @IBOutlet weak var eventLabel: UILabel!
    @IBOutlet weak var eventDateLabel: UILabel!

    override func awakeFromNib() {
        super.awakeFromNib()
    }

    override func setSelected(selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
}
