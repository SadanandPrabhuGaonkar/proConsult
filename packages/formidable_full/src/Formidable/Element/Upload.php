<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator;
use \Concrete\Package\FormidableFull\Src\Helpers\FileImporter as FormidableFileImporter;
use \Concrete\Core\Http\Service\Json as Json;
use \Concrete\Core\File\Importer as FileImporter;
use \Concrete\Core\File\Version as FileVersion;
use \Concrete\Core\File\File;
use \Concrete\Core\File\Set\Set as FileSet;
use \Concrete\Core\File\Image\Thumbnail\Type\Type as ImageType;
use Core;
use Config;
use URL;
use Localization;


class Upload extends Element {

	public $element_text = 'Upload Field';
	public $element_type = 'upload';
	public $element_group = 'Special Elements';

	private $max_uploaded_files = 10;

	private $preview_extensions = array('jpg', 'png', 'gif');

	public $properties = array(
		'label' => true,
		'label_hide' => true,
		'required' => true,
		'tooltip' => true,
		'fileset' => true,
		'min_max' => true,
		'allowed_extensions' => true,
		'handling' => true,
		'errors' => array(
			'empty' => true,
			'extension' => true,
		)
	);

	public $dependency = array(
		'has_value_change' => false
	);

	public function __construct($elementID = 0) {
		$this->properties['allowed_extensions'] = 'jpg, gif, jpeg, png, tiff, docx, doc, xls, xlsx, csv, pdf, zip';
		$this->properties['min_max'] = array(
			'files' => t('Files'),
		);
	}

	public function generateInput()
	{
		$form = Core::make('helper/form');
		$token = Core::make('token');

		if (!$this->getPropertyValue('min_max')) $this->setPropertyValue('max_value', $this->max_uploaded_files);

		$files = array();
		$current = $this->getCurrentFiles();
		if (is_array($current) && count($current)) {
			$detail = ImageType::getByHandle('file_manager_detail');
			foreach ($current as $f) {
				if (is_object($f['fObj'])) {
					$files[] = array(
						'fileID' => $f['fObj']->getFileID(),
						'name' => $f['fObj']->getFileName(),
						'type' => $f['fObj']->getMimeType(),
						'size' => $f['fObj']->getFullSize(),
						'extension' => $f['fObj']->getExtension(),
						'url' => in_array(strtolower($f['fObj']->getExtension()), $this->preview_extensions)?$f['fObj']->getThumbnailURL($detail->getBaseVersion()):''
					);
				}
			}
		}
		$files = Json::encode($files);

		$script = '
			if ($.fn.dropzone) {
				var mocks'.$this->getElementID().' = '.$files.';
				$(\'div[id="'.$this->getHandle().'"]\').dropzone({ 
					url: "'.URL::to('/formidable/dialog/formidable/').'",
					method: "POST",
					uploadMultiple: false,
					addRemoveLinks: true,
					maxFiles: '.$this->getPropertyValue('max_value').',
					acceptedFiles: "'.@implode(',', (array)$this->getAllowedExtensions()).'",					
					init: function() {
						var counter = $(\'[id="'.$this->getHandle().'_counter"]\');											
						this.on("sending", function(file, json, formData){
			            	formData.append("formID", "'.$this->getFormID().'");
			            	formData.append("action", "upload_file");
			                formData.append("ccm_token", "'.$token->generate('formidable_form').'");	
			                formData.append("locale", "'.Localization::getInstance()->getLocale().'");	
			                formData.append("elementID", "'.$this->getElementID().'");	                
			            }),
			            this.on("success", function(file, json){			  
			                var holder = $(\'.message\', this.target);
			                if (!json.success) {
			                	holder.append(\'<div>\'+json.error+\'</div>\').addClass(\'alert alert-danger\').removeClass(\'hide\')
			                	this.removeFile(file);
			                }
			                else {
			                	var holder = $(\'.file_upload\', this.target);	                				                	
			                	holder.append($(\'<input>\').attr({type: \'hidden\', value: json.file, name: \''.$this->getHandle().'[]\'}));
			                	$(file.previewElement).find(\'.dz-error-message\').remove();
			                	this.removeButton();

			                	this.doCount(-1);
			                }
			            }),
			            this.on("error", function(file, json){
							$(file.previewElement).find(\'.dz-error-message\').addClass(\'alert alert-danger\');
			            	this.removeButton();
			            }),
			            this.on("removedfile", function(file) {
			            	var response = file;
			            	if (file.xhr) response = $.parseJSON(file.xhr.responseText);
			            	if (response !== false) {
				            	$(\'input[value="\'+response.file+\'"], input[value="\'+response.fileID+\'"]\', this.target).remove();			            	
				            	$.ajax({ 
									type: "POST",
									url: "'.URL::to('/formidable/dialog/formidable/').'",
									data: {
										formID: '.$this->getFormID().',
										action: "delete_file",
										ccm_token: "'.$token->generate('formidable_form').'",
										elementID: '.$this->getElementID().',
										file: response.file,
										fileID: response.fileID
									},
									success: function() {
										var holder = $(\'.file_upload\', this.target);	                				                	
			                			holder.append($(\'<input>\').attr({type: \'hidden\', value: response.fileID, name: \''.$this->getHandle().'-d[]\'}));
									}								
								});
								this.doCount(1);
							}
			            }),
			            this.removeButton = function() {
			           		$(\'.formidable .file_upload .dz-preview .dz-remove\', this.target).addClass(\'btn btn-danger btn-sm\');
			           	};
			           	this.doCount = function(add) {			           		
							if (counter.length <= 0) return;
							if (counter.attr(\'current\') == undefined) counter.attr(\'current\', parseInt(counter.attr(\'max\')));
							var new_current = parseInt(counter.attr(\'current\')) + add;
							$(\'span\', counter).text(new_current);
							counter.attr(\'current\', new_current);
			           	};

			           	if (mocks'.$this->getElementID().'.length > 0) {
				            for (var i = 0; i < mocks'.$this->getElementID().'.length; i++) {
						        var mock = mocks'.$this->getElementID().'[i];
						        mock.accepted = true;
						        mock.isMock = true;
						        mock.status = Dropzone.ADDED;
						        this.files.push(mock);
						        this.emit(\'addedfile\', mock);
						        this.createThumbnailFromUrl(mock, mock.url);
						        this.emit(\'complete\', mock);
						        var holder = $(\'.file_upload\', this.target);	                				                	
			                	holder.append($(\'<input>\').attr({type: \'hidden\', value: mock.fileID, name: \''.$this->getHandle().'[]\'}));
			                	this.doCount(-1);
						    }
						}	
						
			           	this.removeButton();
			        },
				});
			}
		';

		$this->addJavascript($script);

		$html  = '<div class="file_upload counter_disabled">';
		$html .= '<div class="message alert hide"></div>';
		$html .= '<div id="'.$this->getHandle().'">';
		$html .= '<div class="dz-message">';
		$html .= t('Drag \'n Drop your files here!');
		$html .= '</div>';
		$html .= '<div class="fallback">';
		$html .= '<input name="'.$this->getHandle().'" type="file" multiple />';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';

		$this->setAttribute('input', $html);
	}

	public function getDisplayValue($seperator = ' ', $urlify = true) {
		$files = $this->getCurrentFiles(true);
		if (is_array($files) && count($files)) {
			if (count($files) > 1) return t('%s uploaded files', count($files));
			return $files[0]['name'].' ('.$files[0]['size'].')';
		}
		return '';
	}

	public function getDisplayResult() {
		$html = array();
		$files = $this->getCurrentFiles(true);
		if (is_array($files) && count($files)) {
			$detail = ImageType::getByHandle('file_manager_detail');
			$thumb = ImageType::getByHandle('file_manager_listing');
			foreach ($files as $file) {
				if (is_object($file['fObj'])) {
					if (!in_array(strtolower($file['fObj']->getExtension()), $this->preview_extensions)) $html[] = '<div class="upload_row">'.$file['fObj']->getTypeObject()->getThumbnail().' <a href="'.$file['fObj']->getForceDownloadURL().'" data-toggle="tooltip" title="'.t('Click to download').'">'.$file['name'].'</a> ('.$file['fObj']->getSize().')</div>';
					else $html[] = '<div class="upload_row"><a href="javascript:;" data-toggle="tooltip" title="<img src=\''.$file['fObj']->getThumbnailURL($detail->getBaseVersion()).'\'>"><img src="'.$file['fObj']->getThumbnailURL($thumb->getBaseVersion()).'" class="ccm-generic-thumbnail"></a> <a href="'.$file['fObj']->getForceDownloadURL().'" data-toggle="tooltip" title="'.t('Click to download').'">'.$file['name'].'</a> ('.$file['fObj']->getSize().')</div>';
				}
				else $html[] = '<div class="upload_row">'.$file['name'].' ('.$file['size'].')</div>';
			}
		}
		return @implode('', $html);
	}

	public function getDisplayValueExport($seperator = ' ', $urlify = true) {
		$html = array();
		$files = $this->getCurrentFiles(true);
		if (is_array($files) && count($files)) {
			foreach ($files as $file) {
				if (is_object($file['fObj'])) $html[] = '<a href="'.$file['fObj']->getForceDownloadURL().'">'.$file['name'].'</a> ('.$file['size'].')';
				else $html[] = $file['name'].' ('.$file['size'].')';
			}
		}
		return @implode(PHP_EOL, $html);
	}

	public function getCurrentFiles($unknown = false) {
		$files = array();
		$values = $this->getValue();
		if (is_array($values) && count($values)) {
			foreach ($values as $file) {
				if (!isset($file['fileID'])) continue;
				$f = File::getByID($file['fileID']);
				if (is_object($f)) {
					$fv = $f->getVersion();
					$files[] = array(
						'fObj' => $fv,
						'fileID' => $fv->getFileID(),
						'name' => $fv->getFileName(),
						'size' => $file['filesize']
					);
				}
				elseif ($unknown) {
					$files[] = array(
						'fileID' => 0,
						'name' => $file['filename'],
						'size' => $file['filesize']
					);
				}
			}
		}
		return $files;
	}

	public function uploadFile() {

		if (!isset($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'])) return array('error' => t('File uploaded with errors'));

		$service = Core::make('helper/file');

		$extension = $service->getExtension($_FILES['file']['name']);
		if (!in_array('.'.$extension, $this->getAllowedExtensions())) return array('error' => t('Extension not allowed'));

		$file = pathinfo($_FILES['file']['name']);
		$filename = $file['filename'].'-'.date("YmdHis").'.'.$file['extension'];

		$fi = new FormidableFileImporter();
		$file = $fi->import($_FILES['file']['tmp_name'], $filename, '/formidable_tmp/'.$this->getElementID().'/');
		return $file;
	}

	public function deleteFile() {
		$file = $this->post('file');
		if (empty($file)) return array('error' => t('File not found'));
		$fi = new FormidableFileImporter();
		$file = $fi->delete($file, '/formidable_tmp/'.$this->getElementID().'/');
		return $file;
	}

	public function processFiles() {

		// Move new files to the filemanager
		$return = $this->moveFilesToFilemanager();

		// Add files already uploaded to post data
		$values = $this->getValue();
		if (is_array($values) && count($values)) {
			foreach($values as $fID) {
				$f = File::getByID($fID);
				if (is_object($f)) {
					$fv = $f->getVersion();
					$return['files'][] = array(
						'fileID' => $f->getFileID(),
						'filename' => $f->getFileName(),
						'extension' => $f->getExtension(),
						'filesize' => $f->getSize()
					);
				}
			}
		}

		// Delete files from filemanager if no longer needed.
		$delete = $this->post($this->getHandle().'-d');
		if (is_array($delete) && count($delete)) {
			foreach ($delete as $fID) {
				$f = File::getByID($fID);
				if (is_object($f)) $f->delete();
			}
		}

		return $return;
	}

	private function moveFilesToFilemanager() {

		$errors = $files = array();

		$uploads = $this->getValue();
		if (is_array($uploads) && count($uploads)) {

			// Now do some dirty sh*t.
			// Because the form is submitted by Ajax de content-length header is set to a minimal.
			// FileImporter checks this content-length header to make sure there is no illegal use.
			// So, I have to change this to make uploading possible....
			$_SERVER['CONTENT_LENGTH'] = Core::make('helper/number')->getBytes(ini_get('post_max_size'));

			foreach ($uploads as $file) {
				$pathinfo = pathinfo($file);
				$filename = substr($pathinfo['filename'], 0, -15).'.'.$pathinfo['extension'];

				$path = DIR_FILES_UPLOADED_STANDARD.'/formidable_tmp/'.$this->getElementID().'/'.$file;
				if (file_exists($path)) {
					$importer = new FileImporter();
					$result = $importer->import($path, $filename);
					if (is_object($result)) {

						// Assign to Fileset
						if (!empty($this->getPropertyValue('fileset')) && !empty($this->getPropertyValue('fileset_value'))) {
							$fs = FileSet::getByID(intval($this->getPropertyValue('fileset_value')));
						}
						if (!is_object($fs)) {
							$fs = FileSet::createAndGetSet(t('Uploaded Files'), FileSet::TYPE_PUBLIC);
						}

						$fs->addFileToSet($result);

						// Remove tmp file
						$fi = new FormidableFileImporter();
						$file = $fi->delete($file, '/formidable_tmp/'.$this->getElementID().'/');

						$files[] = array(
							'fileID' => $result->getFileID(),
							'filename' => $filename,
							'extension' => $result->getExtension(),
							'filesize' => $result->getSize()
						);
					} else {
						$errors[] = t('%s: %s', $filename, FileImporter::getErrorMessage($result));
					}
				}
			}
		}
		return array(
			'errors' => $errors,
			'files' => $files
		);
	}

	private function getAllowedExtensions() {
		$allowed_ext = (array)@explode(';', Config::get('concrete.upload.extensions'));
		if ($this->getPropertyValue('allowed_extensions')) $allowed_ext = (array)@explode(',', $this->getPropertyValue('allowed_extensions_value'));
		foreach ((array)$allowed_ext as $key => $ext) {
			$ext = preg_replace(array('/ /', '/\*\./', '/\./'), '', $ext);
			$allowed_ext[$key] = strpos('.', $ext)!==false?$ext:'.'.$ext;
		}
		return $allowed_ext;
	}
}
