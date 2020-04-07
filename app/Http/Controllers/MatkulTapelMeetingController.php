<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	use Siakad\MatkulTapel;
	use Siakad\MatkulTapelMeeting;
	
	class MatkulTapelMeetingController extends Controller
	{		
		use \Siakad\ZoomTrait;
		
		public function start(MatkulTapelMeeting $meeting)
		{
			$start_url = $meeting -> start_url;
			if($meeting -> started == 'y') return Redirect::back() -> with('warning', 'Conference sudah dimulai');
			$meeting -> update(['started' => 'y']);
			return Redirect::to($start_url);
		}
		
		public function index($matkul_tapel_id)
		{
			$kelas = MatkulTapel::find($matkul_tapel_id);
			
			return view('matkul.tapel.meeting.index', compact('kelas'));
		}
		public function create($matkul_tapel_id)
		{
			$kelas = MatkulTapel::find($matkul_tapel_id);
			
			return view('matkul.tapel.meeting.create', compact('kelas'));
		}
		public function store(Request $request, $matkul_tapel_id)
		{
			// $kelas = MatkulTapel::find($matkul_tapel_id);
			$input = $request -> all();
			
			$create_data = [];
            if ( ! empty( $input['alternative_host_ids'] ) ) {
                if ( count( $input['alternative_host_ids'] ) > 1 ) {
                    $alternative_host_ids = implode( ",", $input['alternative_host_ids'] );
					} else {
                    $alternative_host_ids = $input['alternative_host_ids'][0];
				}
			}
            $create_data['topic']      = $input['topic'];
            $create_data['agenda']     = ! empty( $input['agenda'] ) ? $input['agenda'] : "";
            $create_data['type']       = ! empty( $input['type'] ) ? $input['type'] : 1;
			
            $create_data['password']   = ! empty( $input['password'] ) ? $input['password'] : "";
            $create_data['duration']   = ! empty( $input['duration'] ) ? $input['duration'] : 40;
            $create_data['settings']   = array(
			'join_before_host'  => ! empty( $input['join_before_host'] ) ? true : false,
			'host_video'        => ! empty( $input['option_host_video'] ) ? true : false,
			'participant_video' => ! empty( $input['option_participants_video'] ) ? true : false,
			'mute_upon_entry'   => ! empty( $input['option_mute_participants'] ) ? true : false,
			'enforce_login'     => ! empty( $input['option_enforce_login'] ) ? true : false,
			'auto_recording'    => ! empty( $input['option_auto_recording'] ) ? $input['option_auto_recording'] : "none",
			'alternative_hosts' => isset( $alternative_host_ids ) ? $alternative_host_ids : ""
            );
			
			$response = $this -> sendRequest('/v2/users/me/meetings', $create_data);
			
			if(!$response)
			return Redirect::route('matkul.tapel.meeting', $matkul_tapel_id) -> withErrors(['ERROR' => 'Terjadi kesalahan']);
			
			$store = [
			'matkul_tapel_id' => $matkul_tapel_id,
			'meeting_id' => $response -> id,
			'host_id' => $response -> host_id,
			'uuid' => $response -> uuid,
			'topic' => $response -> topic,
			'start_url' => $response -> start_url,
			'join_url' => $response -> join_url
			];
			
			MatkulTapelMeeting::create($store);
			return Redirect::route('matkul.tapel.meeting', $matkul_tapel_id) -> with('success', 
			'Video Conference berhasil dibuat');
		}
		
		public function createAMeeting($data = array() ) {
            $post_time  = $data['start_date'];
			$start_time = gmdate( "Y-m-d\TH:i:s", strtotime( $post_time ) );
            $createAMeetingArray = array();
            if ( ! empty( $data['alternative_host_ids'] ) ) {
                if ( count( $data['alternative_host_ids'] ) > 1 ) {
                    $alternative_host_ids = implode( ",", $data['alternative_host_ids'] );
					} else {
                    $alternative_host_ids = $data['alternative_host_ids'][0];
				}
			}
            $createAMeetingArray['topic']      = $data['topic'];
            $createAMeetingArray['agenda']     = ! empty( $data['agenda'] ) ? $data['agenda'] : "";
            $createAMeetingArray['type']       = ! empty( $data['type'] ) ? $data['type'] : 2; //Scheduled
            $createAMeetingArray['start_time'] = $start_time;
            $createAMeetingArray['timezone']   = $data['timezone'] ?? config('app.timezone');
            $createAMeetingArray['password']   = ! empty( $data['password'] ) ? $data['password'] : "";
            $createAMeetingArray['duration']   = ! empty( $data['duration'] ) ? $data['duration'] : 60;
            $createAMeetingArray['settings']   = array(
			'join_before_host'  => ! empty( $data['join_before_host'] ) ? true : false,
			'host_video'        => ! empty( $data['option_host_video'] ) ? true : false,
			'participant_video' => ! empty( $data['option_participants_video'] ) ? true : false,
			'mute_upon_entry'   => ! empty( $data['option_mute_participants'] ) ? true : false,
			'enforce_login'     => ! empty( $data['option_enforce_login'] ) ? true : false,
			'auto_recording'    => ! empty( $data['option_auto_recording'] ) ? $data['option_auto_recording'] : "none",
			'alternative_hosts' => isset( $alternative_host_ids ) ? $alternative_host_ids : ""
            );
            return $this->sendRequest('/v2/users/me/meetings', $createAMeetingArray);
		}
		
		public function createMeeting()
		{
			$z = $this->createAMeeting(
			array(
			'start_date'=>date("Y-m-d h:i:s", strtotime('tomorrow')),
			'topic'=>'Example Test Meeting 2'
			)
			);
			dd($z);
		}
	}
