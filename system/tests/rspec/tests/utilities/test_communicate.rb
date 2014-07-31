require './bootstrap.rb'

feature 'Communicate' do

	before(:each) do
		cp_session
		@page = Communicate.new
		@page.load

		@page.should be_displayed
		@page.title.text.should eq 'Communicate ✱ Required Fields'
		@page.should have_subject
		@page.should have_body
		@page.should have_mailtype
		@page.should have_wordwrap
		@page.should have_from_email
		@page.should have_attachment
		@page.should have_recipient
		@page.should have_cc
		@page.should have_bcc
		@page.should have_member_groups
		@page.should have_submit_button
	end

	it "shows the Communicate page" do
		@page.mailtype.value.should eq 'text'
		@page.wordwrap.checked?.should eq true
	end

	it "shows errors when required fields are not populated" do
		@page.from_email.set ''
		@page.submit_button.click

		@page.should have_alert
		@page.should have_css 'div.alert.issue'
		@page.alert.should have_text "An error occurred"

		@page.subject.first(:xpath, ".//../..")[:class].should include 'invalid'
		@page.subject.first(:xpath, ".//..").should have_css 'em.ee-form-error-message'
		@page.subject.first(:xpath, ".//..").should have_text 'field is required.'

		@page.body.first(:xpath, ".//../..")[:class].should include 'invalid'
		@page.body.first(:xpath, ".//..").should have_css 'em.ee-form-error-message'
		@page.body.first(:xpath, ".//..").should have_text 'field is required.'

		@page.from_email.first(:xpath, ".//../..")[:class].should include 'invalid'
		@page.from_email.first(:xpath, ".//..").should have_css 'em.ee-form-error-message'
		@page.from_email.first(:xpath, ".//..").should have_text 'field is required.'

		@page.recipient.first(:xpath, ".//../..")[:class].should include 'invalid'
		@page.recipient.first(:xpath, ".//..").should have_css 'em.ee-form-error-message'
		@page.recipient.first(:xpath, ".//..").should have_text 'You left some fields empty.'

		@page.submit_button[:value].should eq 'Fix Errors, Please'
	end

	it "validates email fields" do
		my_email = 'not an email'

		@page.from_email.set my_email
		@page.recipient.set my_email
		@page.cc.set my_email
		@page.bcc.set my_email
		@page.submit_button.click

		@page.should have_alert
		@page.should have_css 'div.alert.issue'
		@page.alert.should have_text "An error occurred"

		@page.from_email.value.should eq my_email
		@page.from_email.first(:xpath, ".//../..")[:class].should include 'invalid'
		@page.from_email.first(:xpath, ".//..").should have_css 'em.ee-form-error-message'
		@page.from_email.first(:xpath, ".//..").should have_text 'field must contain a valid email address.'

		@page.recipient.value.should eq my_email
		@page.recipient.first(:xpath, ".//../..")[:class].should include 'invalid'
		@page.recipient.first(:xpath, ".//..").should have_css 'em.ee-form-error-message'
		@page.recipient.first(:xpath, ".//..").should have_text 'field must contain all valid email addresses.'

		@page.cc.value.should eq my_email
		@page.cc.first(:xpath, ".//../..")[:class].should include 'invalid'
		@page.cc.first(:xpath, ".//..").should have_css 'em.ee-form-error-message'
		@page.cc.first(:xpath, ".//..").should have_text 'field must contain all valid email addresses.'

		@page.bcc.value.should eq my_email
		@page.bcc.first(:xpath, ".//../..")[:class].should include 'invalid'
		@page.bcc.first(:xpath, ".//..").should have_css 'em.ee-form-error-message'
		@page.bcc.first(:xpath, ".//..").should have_text 'field must contain all valid email addresses.'
	end

	it "denies multiple email addresses in from field" do
		my_email = 'one@nomail.com,two@nomail.com'

		@page.from_email.set my_email
		@page.submit_button.click

		@page.should have_alert
		@page.should have_css 'div.alert.issue'
		@page.alert.should have_text "An error occurred"

		@page.from_email.value.should eq my_email
		@page.from_email.first(:xpath, ".//../..")[:class].should include 'invalid'
		@page.from_email.first(:xpath, ".//..").should have_css 'em.ee-form-error-message'
		@page.from_email.first(:xpath, ".//..").should have_text 'field must contain a valid email address.'
	end

	it "accepts multiple email addresses" do
		my_email = 'one@nomail.com,two@nomail.com'
		@page.recipient.set my_email
		@page.cc.set my_email
		@page.bcc.set my_email
		@page.submit_button.click

		@page.recipient.value.should eq my_email
		@page.recipient.first(:xpath, ".//../..")[:class].should_not include 'invalid'
		@page.recipient.first(:xpath, ".//..").should_not have_css 'em.ee-form-error-message'
		@page.recipient.first(:xpath, ".//..").should_not have_text 'field must contain all valid email addresses.'

		@page.cc.value.should eq my_email
		@page.cc.first(:xpath, ".//../..")[:class].should_not include 'invalid'
		@page.cc.first(:xpath, ".//..").should_not have_css 'em.ee-form-error-message'
		@page.cc.first(:xpath, ".//..").should_not have_text 'field must contain all valid email addresses.'

		@page.bcc.value.should eq my_email
		@page.bcc.first(:xpath, ".//../..")[:class].should_not include 'invalid'
		@page.bcc.first(:xpath, ".//..").should_not have_css 'em.ee-form-error-message'
		@page.bcc.first(:xpath, ".//..").should_not have_text 'field must contain all valid email addresses.'
	end

	it "allows recipient to be empty if a group is selected" do
		@page.member_groups[0].set(true)
		@page.submit_button.click

		@page.recipient.first(:xpath, ".//../..")[:class].should_not include 'invalid'
		@page.recipient.first(:xpath, ".//..").should_not have_css 'em.ee-form-error-message'
		@page.recipient.first(:xpath, ".//..").should_not have_text 'You left some fields empty.'
	end
end