/**
 * Created by Administrator on 2018/9/15.
 */

//Using DOM 2 event listener

//Contact radio buttons event
var contacts = document.joinform.contactmethod;
for(index = 0; index < contacts.length; index++)
    contacts[index].addEventListener("change", contactChange, false);

//Magazine checkbox event
joinform.magazine.addEventListener("click", magazineChange, false);

//Help pop-up events
joinform.preferredcontact.addEventListener("mousemove", helpPopup, false);
joinform.preferredcontact.addEventListener("mouseout", helpPopdown, false);

joinform.mobilenum.addEventListener("mouseover", helpPopup, false);
joinform.mobilenum.addEventListener("mouseout", helpPopdown, false);

joinform.phonenum.addEventListener("mouseover", helpPopup, false);
joinform.phonenum.addEventListener("mouseout", helpPopdown, false);

joinform.email.addEventListener("mouseover", helpPopup, false);
joinform.email.addEventListener("mouseout", helpPopdown, false);

joinform.streetaddr.addEventListener("mouseover", helpPopup, false);
joinform.streetaddr.addEventListener("mouseout", helpPopdown, false);

joinform.suburbstate.addEventListener("mouseover", helpPopup, false);
joinform.suburbstate.addEventListener("mouseout", helpPopdown, false);

joinform.postcode.addEventListener("mouseover", helpPopup, false);
joinform.postcode.addEventListener("mouseout", helpPopdown, false);

joinform.joinusername.addEventListener("mouseover", helpPopup, false);
joinform.joinusername.addEventListener("mouseout", helpPopdown, false);

joinform.userpass.addEventListener("mouseover", helpPopup, false);
joinform.userpass.addEventListener("mouseout", helpPopdown, false);

joinform.verifypass.addEventListener("mouseover", helpPopup, false);
joinform.verifypass.addEventListener("mouseout", helpPopdown, false);