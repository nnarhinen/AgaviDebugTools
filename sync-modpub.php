<?php
/**
 * Copy and paste all needed files from DEV to Demo - for modpub directory
 *
 * @author    Daniel Ancuta <daniel.ancuta@whisnet.pl>
 * @author    Veikko Mäkinen <veikko@veikko.fi>
 * @copyright Authors
 * @version   0.1
 */

/**
 * Paths
 */
# Demo
$demoModpubPath = dirname(__FILE__).'/demo/pub/modpub';
# Dev
$modpubPath     = dirname(__FILE__).'/modpub';

# Remove all files and directories from demo
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($demoModpubPath), RecursiveIteratorIterator::CHILD_FIRST);

for(; $iterator->valid(); $iterator->next()) {
  $rdi = $iterator->getInnerIterator();

  if ( (strpos( $rdi->getSubpathname(), '.svn' )!==false) || $iterator->isDot() ) {
    continue;
  }

  if ( $rdi->isFile() ) {
    unlink( $rdi->getPathname() );
  } else {
    rmdir( $rdi->getPathname() );
  }
}

# Create our ADT dir in modpub :)
mkdir("./demo/pub/modpub/Adt");


# Copy and paste all needed files from dev to demo
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($modpubPath), RecursiveIteratorIterator::SELF_FIRST);

for(; $iterator->valid(); $iterator->next()) {
  $rdi = $iterator->getInnerIterator();

  if ( (strpos( $rdi->getSubpathname(), '.svn' )!==false) || $iterator->isDot() ) {
    continue;
  }

  if ( $rdi->isDir() ) {
    mkdir('./demo/pub/modpub/Adt/'.$rdi->getSubPathname());
  } else {
    copy( $rdi->getPathname(), './demo/pub/modpub/Adt/'.$rdi->getSubPathname() );
  }
}
?>
