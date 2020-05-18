<?php

class HappyForms_Email_Message {

	/**
	 * The sender address.
	 *
	 * @since 1.4.3
	 *
	 * @var string
	 */
	private $from;

	/**
	 * The sender name.
	 *
	 * @since 1.4.3
	 *
	 * @var string
	 */
	private $from_name;

	/**
	 * The reply-to address.
	 *
	 * @since 1.4.3
	 *
	 * @var string
	 */
	private $reply_to;

	/**
	 * The list of recipients.
	 *
	 * @since 1.4.3
	 *
	 * @var array
	 */
	private $to;

	/**
	 * The list of cc addresses.
	 *
	 * @since 1.4.3
	 *
	 * @var array
	 */
	private $ccs;

	/**
	 * The list of bcc addresses.
	 *
	 * @since 1.4.3
	 *
	 * @var array
	 */
	private $bccs;

	/**
	 * The email subject.
	 *
	 * @since 1.4.3
	 *
	 * @var string
	 */
	private $subject;

	/**
	 * The email content type.
	 *
	 * @since 1.4.3
	 *
	 * @var string
	 */
	private $content_type = 'text/html';

	/**
	 * The email message content.
	 *
	 * @since 1.4.3
	 *
	 * @var string
	 */
	private $content;

	private $attachments = array();

	/**
	 * The submission message this email is linked to.
	 *
	 * @since 1.4.3
	 *
	 * @var array
	 */
	public $message;

	public function __construct( $message = array() ) {
		$this->from = '';
		$this->to = '';
		$this->ccs = array();
		$this->bccs = array();
		$this->subject = '';
		$this->content = '';
		$this->message = $message;
	}

	public function set_from( $email, $name = '' ) {
		$from = apply_filters( 'happyforms_email_from', $email, $this->message );
		$this->from = $from;
	}

	public function get_from() {
		return $this->from;
	}

	public function set_from_name( $name ) {
		$from_name = apply_filters( 'happyforms_email_from_name', $name, $this->message );
		$this->from_name = $from_name;
	}

	public function get_from_name() {
		return $this->from_name;
	}

	public function set_to( $to ) {
		/**
		 * Filter the list of recipients for this email message.
		 *
		 * @since 1.4.3
		 *
		 * @param array $recipients Current list of recipients.
		 * @param array $message    The submission this email was triggered from.
		 *
		 * @return array
		 */
		$to = apply_filters( 'happyforms_email_to', $to, $this->message );

		$this->to = trim( $to );
	}

	public function get_to() {
		return $this->to;
	}

	public function set_ccs( $ccs = array() ) {
		if ( is_string( $ccs ) ) {
			$ccs = array( $ccs );
		}

		/**
		 * Filter the list of recipients for this email message.
		 *
		 * @since 1.4.3
		 *
		 * @param array $recipients Current list of recipients.
		 * @param array $message    The submission this email was triggered from.
		 *
		 * @return array
		 */
		$ccs = apply_filters( 'happyforms_email_ccs', $ccs, $this->message );

		$this->ccs = array_map( 'trim', $ccs );
	}

	public function get_ccs() {
		return $this->ccs;
	}

	public function set_bccs( $bccs = array() ) {
		if ( is_string( $bccs ) ) {
			$bccs = array( $bccs );
		}

		/**
		 * Filter the list of recipients for this email message.
		 *
		 * @since 1.4.3
		 *
		 * @param array $recipients Current list of recipients.
		 * @param array $message    The submission this email was triggered from.
		 *
		 * @return array
		 */
		$bccs = apply_filters( 'happyforms_email_bccs', $bccs, $this->message );

		$this->bccs = array_map( 'trim', $bccs );
	}

	public function get_bccs() {
		return $this->bccs;
	}

	public function set_reply_to( $reply_to = array() ) {
		if ( is_string( $reply_to ) ) {
			$reply_to = array( $reply_to );
		}

		/**
		 * Filter the list of recipients for this email message.
		 *
		 * @since 1.4.3
		 *
		 * @param array $recipients Current list of recipients.
		 * @param array $message    The submission this email was triggered from.
		 *
		 * @return array
		 */
		$reply_to = apply_filters( 'happyforms_email_reply_to', $reply_to, $this->message );

		$this->reply_to = array_map( 'trim', $reply_to );
	}

	public function get_reply_to() {
		return $this->reply_to;
	}

	public function set_subject( $subject = '' ) {
		$subject = trim( $subject );

		/**
		 * Filter the subject for this email message.
		 *
		 * @since 1.4.3
		 *
		 * @param string $subject Current subject.
		 * @param array  $message    The submission this email was triggered from.
		 * @param array  $recipients The address this email is being sent to.
		 *
		 * @return string
		 */
		$subject = apply_filters( 'happyforms_email_subject', $subject, $this->message, $this->to );

		$this->subject = $subject;
	}

	public function get_subject() {
		return $this->subject;
	}

	public function set_content( $content = '' ) {
		$content = trim( $content );

		/**
		 * Filter the content for this email message.
		 *
		 * @since 1.4.3
		 *
		 * @param string $content    Current content.
		 * @param array  $message    The submission this email was triggered from.
		 * @param array  $recipients The address this email is being sent to.
		 *
		 * @return string
		 */
		$content = apply_filters( 'happyforms_email_content', $content, $this->message, $this->to );

		$this->content = $content;
	}

	public function get_content() {
		return $this->content;
	}

	public function get_response() {
		return $this->message;
	}

	public function get_headers() {
		$headers = array();

		if ( ! empty( $this->reply_to ) ) {
			array_push( $headers, 'Reply-To: ' . implode( ', ', $this->reply_to ) );
		}

		if ( ! empty( $this->ccs ) ) {
			array_push( $headers, 'Cc: ' . implode( ', ', $this->ccs ) );
		}

		if ( ! empty( $this->bccs ) ) {
			array_push( $headers, 'Bcc: ' . implode( ', ', $this->bccs ) );
		}

		return $headers;
	}

	public function add_attachment( $path ) {
		$this->attachments[] = $path;
	}

	public function get_attachments() {
		return $this->attachments;
	}

	public function get_content_type() {
		/**
		 * Filter the content type for this email message.
		 *
		 * @since 1.4.3
		 *
		 * @param string $content_type Current content type.
		 * @param array  $message      The submission this email was triggered from.
		 *
		 * @return string
		 */
		$content_type = apply_filters( 'happyforms_email_content_type', $this->content_type, $this->message );

		return $content_type;
	}

	public function send() {
		add_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

		if ( $this->from ) {
			add_filter( 'wp_mail_from', array( $this, 'get_from' ) );
		}

		if ( $this->from_name ) {
			add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		}

		$headers = $this->get_headers();
		$attachments = $this->get_attachments();
		$result = wp_mail( $this->to, $this->subject, $this->content, $headers, $attachments );

		remove_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

		if ( $this->from ) {
			remove_filter( 'wp_mail_from', array( $this, 'get_from' ) );
		}

		if ( $this->from_name ) {
			remove_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		}

		return $result;
	}

}
