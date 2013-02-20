//
//  EventPointAnnotation.h
//  cookingPlace
//
//  Created by Daniel Künkel on 26.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import <MapKit/MapKit.h>
#import "EventInfo.h"

@interface EventPointAnnotation : MKPointAnnotation
{
    EventInfo *data;
}
@property (retain, readwrite) EventInfo *data;
@end
