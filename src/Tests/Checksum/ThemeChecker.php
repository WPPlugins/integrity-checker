<?php
namespace WPChecksum;

/**
 * Class ThemeChecker
 * @package WPChecksum
 */
class ThemeChecker extends BaseChecker
{

    /**
     * ThemeChecker constructor.
     *
     * @param \integrityChecker\APIClient $apiClient
     */
    public function __construct($apiClient)
    {
        $this->basePath = get_theme_root();

	    $this->softIssues = array(
		    'readme.txt' => '*',
	    );

        parent::__construct($apiClient);
    }

    /**
     * Check a single Theme
     *
     * @param $slug
     * @param $theme
     * @return array
     */
    public function check($slug, $theme)
    {
        $ret = array();
        $ret['type'] = 'theme';
        $ret['slug'] = $slug;
        $ret['name'] = $theme->Name;
        $ret['version'] = $theme->Version;

        $original = $this->getOriginalChecksums('theme', $slug, $theme->Version);
        if ($original) {
            $local = $this->getLocalChecksums($this->basePath . "/$slug");
            $changeSet = $this->getChangeSet($original, $local);
            $ret['status']    = 'checked';
            $ret['message']   = '';
            $ret['changeset'] = $changeSet;
        } else {
            $ret['status']    =  'unchecked';
            $ret['message']   = 'Theme original not found';
            $ret['error']     = $this->apiClient->getLastError();
            $ret['changeset'] = array();
        }

        return $ret;
    }
}