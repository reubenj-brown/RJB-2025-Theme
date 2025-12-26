// Story Templates Shared JavaScript
// Image caption and credit handling for all story templates

document.addEventListener('DOMContentLoaded', function() {
    // Process all images in story content for automatic captions
    const storyImages = document.querySelectorAll('.story-content-inner img');

    storyImages.forEach(function(img) {

        // Get WordPress caption from multiple possible sources
        let captionText = '';
        let credit = '';

        // Method 1: Check if image is inside a WordPress figure with figcaption
        const figure = img.closest('figure');
        if (figure) {
            const figcaption = figure.querySelector('figcaption');
            if (figcaption) {
                captionText = figcaption.textContent.trim();
            }
        }

        // Method 2: Check for wp-caption-text (WordPress standard)
        const wpCaption = img.parentNode.querySelector('.wp-caption-text');
        if (wpCaption && !captionText) {
            captionText = wpCaption.textContent.trim();
        }

        // Method 3: Check parent for wp-caption div
        const wpCaptionDiv = img.closest('.wp-caption');
        if (wpCaptionDiv && !captionText) {
            const captionElement = wpCaptionDiv.querySelector('.wp-caption-text');
            if (captionElement) {
                captionText = captionElement.textContent.trim();
            }
        }

        // Method 4: Check for WordPress blocks caption
        const wpBlockCaption = img.parentNode.querySelector('.wp-element-caption');
        if (wpBlockCaption && !captionText) {
            captionText = wpBlockCaption.textContent.trim();
        }

        // Method 4b: Check for wp-block-image caption
        const wpBlockImage = img.closest('.wp-block-image');
        if (wpBlockImage && !captionText) {
            const blockCaption = wpBlockImage.querySelector('figcaption');
            if (blockCaption) {
                captionText = blockCaption.textContent.trim();
            }
        }

        // Method 4c: Check for UAGB image caption
        const uagbBlock = img.closest('.wp-block-uagb-image');
        if (uagbBlock && !captionText) {
            // Found UAGB block, searching for caption...

            // Check for figcaption within UAGB block
            const uagbCaption = uagbBlock.querySelector('figcaption');
            if (uagbCaption) {
                captionText = uagbCaption.textContent.trim();
            }

            // Check for UAGB-specific caption classes
            const uagbCaptionText = uagbBlock.querySelector('.uagb-image-caption, .wp-block-uagb-image__caption');
            if (uagbCaptionText && !captionText) {
                captionText = uagbCaptionText.textContent.trim();
            }

            // Debug: Log all elements within UAGB block
        }

        // Method 5: Check for caption in surrounding paragraph
        const nextElement = img.parentNode.nextElementSibling;
        if (nextElement && nextElement.tagName === 'P' && nextElement.classList.contains('caption') && !captionText) {
            captionText = nextElement.textContent.trim();
        }

        // Method 6: Check for data-caption attribute
        if (!captionText && img.getAttribute('data-caption')) {
            captionText = img.getAttribute('data-caption').trim();
        }

        // Method 6b: Check for WordPress attachment attributes
        if (!captionText) {
            // Check for data-attachment_id attribute
            const attachmentId = img.getAttribute('data-attachment-id') || img.getAttribute('data-id');
            if (attachmentId) {
            }

            // Check for WordPress-specific attributes that might contain caption
            const wpCaption = img.getAttribute('data-caption');
            const wpTitle = img.getAttribute('data-title');
            const wpDescription = img.getAttribute('data-description');

            if (wpCaption) {
                captionText = wpCaption.trim();
            } else if (wpDescription) {
                captionText = wpDescription.trim();
            }
        }

        // Comprehensive debugging disabled - caption detection working

        // Debug: show what we found

        // If no caption found from DOM, try WordPress REST API
        if (!captionText) {

            // Extract filename to search for attachment
            const filename = img.src.split('/').pop().split('?')[0]; // Remove query params
            const filenameWithoutExt = filename.replace(/\.[^.]+$/, ''); // Remove extension

            // Search for attachment by filename
            fetch('/wp-json/wp/v2/media?search=' + encodeURIComponent(filenameWithoutExt) + '&per_page=1')
                .then(response => response.json())
                .then(mediaArray => {
                    if (mediaArray && mediaArray.length > 0) {
                        const mediaData = mediaArray[0];

                        // Check for caption in WordPress media data
                        let foundCaption = '';
                        if (mediaData.caption && mediaData.caption.rendered) {
                            foundCaption = mediaData.caption.rendered.replace(/<[^>]*>/g, '').trim(); // Strip HTML
                        }

                        // Check for source in meta fields with comprehensive debugging
                        let foundSource = '';
                        // Check for source in meta fields

                        // Check for source in the new REST API field we registered
                        if (mediaData.media_source) {
                            foundSource = mediaData.media_source;
                        } else if (mediaData.meta) {
                            // Fallback: check meta fields
                            foundSource = mediaData.meta._media_source ||
                                         mediaData.meta.media_source ||
                                         mediaData.meta.source ||
                                         '';

                            if (foundSource) {
                            } else {
                            }
                        } else {
                        }

                        // Also check if source is in other locations
                        if (!foundSource && mediaData.source) {
                            foundSource = mediaData.source;
                        }

                        // Check custom fields if available
                        if (!foundSource && mediaData.acf && mediaData.acf.source) {
                            foundSource = mediaData.acf.source;
                        }

                        // Update caption display if we found caption data
                        if (foundCaption || foundSource) {
                            updateCaptionDisplay(img, foundCaption, foundSource);
                        } else {
                        }
                    } else {

                        for (let i = 0; i < img.attributes.length; i++) {
                            const attr = img.attributes[i];
                        }
                    }
                })
                .catch(error => {
                });
        }

        // Try to extract credit from caption text using common patterns
        if (captionText && (captionText.includes('Photo:') || captionText.includes('Credit:') || captionText.includes('Source:'))) {
            const parts = captionText.split(/(?:Photo:|Credit:|Source:)/i);
            if (parts.length > 1) {
                captionText = parts[0].trim();
                credit = parts[1].trim();
            }
        }

        // Get WordPress media library source field
        // We need to make an AJAX call to get the media source since it's not in the DOM
        if (!credit) {
            // Try to get attachment ID from various WordPress attributes
            let attachmentId = null;

            // Check for wp-image-XXXX class (WordPress standard)
            const imageClasses = img.className.split(' ');
            for (let className of imageClasses) {
                if (className.startsWith('wp-image-')) {
                    attachmentId = className.replace('wp-image-', '');
                    break;
                }
            }

            // If we have an attachment ID, fetch the source
            if (attachmentId) {
                // For now, use a placeholder - we'll need to implement AJAX call
                credit = 'Loading source...';

                // Make AJAX call to get media source
                fetch('/wp-json/wp/v2/media/' + attachmentId)
                    .then(response => response.json())
                    .then(data => {

                        // Check multiple possible locations for source field
                        let source = '';

                        // Method 1: Check meta field
                        if (data.meta && data.meta._media_source) {
                            source = data.meta._media_source;
                        }

                        // Method 2: Check meta field without underscore
                        if (!source && data.meta && data.meta.media_source) {
                            source = data.meta.media_source;
                        }

                        // Method 3: Check meta field as 'source'
                        if (!source && data.meta && data.meta.source) {
                            source = data.meta.source;
                        }

                        // Method 4: Check acf fields if using ACF
                        if (!source && data.acf && data.acf.source) {
                            source = data.acf.source;
                        }

                        // Method 5: Check top level source field
                        if (!source && data.source) {
                            source = data.source;
                        }


                        if (source) {
                            // Update the credit text if we found a source
                            const existingCaption = img.nextSibling;
                            if (existingCaption && existingCaption.classList.contains('story-image-caption')) {
                                const creditElement = existingCaption.querySelector('.credit-text');
                                if (creditElement) {
                                    creditElement.textContent = source;
                                }
                            }
                        } else {
                            // Remove credit element if no source found
                            const existingCaption = img.nextSibling;
                            if (existingCaption && existingCaption.classList.contains('story-image-caption')) {
                                const creditElement = existingCaption.querySelector('.credit-text');
                                if (creditElement) {
                                    creditElement.remove();
                                }
                            }
                        }
                    })
                    .catch(error => {
                        // Remove loading text if API fails
                        const existingCaption = img.nextSibling;
                        if (existingCaption && existingCaption.classList.contains('story-image-caption')) {
                            const creditElement = existingCaption.querySelector('.credit-text');
                            if (creditElement) {
                                creditElement.remove();
                            }
                        }
                    });
            } else {
                // No attachment ID found, no credit to show
                credit = '';
            }
        }

        // Only show caption if we have actual content
        if (captionText || credit) {
            // Create caption div
            const captionDiv = document.createElement('div');
            captionDiv.className = 'story-image-caption';

            // Add caption and credit with pipe separator
            let captionHTML = '';
            if (captionText) {
                captionHTML += `<span class="caption-text">${captionText}</span>`;
            }
            if (credit) {
                if (captionText) {
                    captionHTML += `<span class="pipe-separator"> | </span>`;
                }
                captionHTML += `<span class="credit-text">${credit}</span>`;
            }
            captionDiv.innerHTML = captionHTML;

            // Insert caption inside UAGB block (if exists) or after image
            const uagbBlock = img.closest('.wp-block-uagb-image');
            if (uagbBlock) {
                // For UAGB blocks, append inside the block (not as a sibling)
                uagbBlock.appendChild(captionDiv);
            } else {
                // For regular images, insert after the image
                img.parentNode.insertBefore(captionDiv, img.nextSibling);
            }
        }
    });

    // Helper function to update caption display
    function updateCaptionDisplay(img, captionText, creditText) {
        const uagbBlock = img.closest('.wp-block-uagb-image');

        // Remove any existing caption
        if (uagbBlock) {
            // For UAGB blocks, check inside the block
            const existingCaption = uagbBlock.querySelector('.story-image-caption');
            if (existingCaption) {
                existingCaption.remove();
            }
        } else {
            // For regular images, check next sibling
            let nextSibling = img.parentNode.nextElementSibling;
            if (nextSibling && nextSibling.classList.contains('story-image-caption')) {
                nextSibling.remove();
            }
        }

        // Only create caption if we have caption text or credit
        if (captionText || creditText) {
            const captionDiv = document.createElement('div');
            captionDiv.className = 'story-image-caption';

            let captionHTML = '';
            if (captionText) {
                captionHTML += `<span class="caption-text">${captionText}</span>`;
            }
            if (creditText) {
                if (captionText) {
                    captionHTML += `<span class="pipe-separator"> | </span>`;
                }
                captionHTML += `<span class="credit-text">${creditText}</span>`;
            }

            captionDiv.innerHTML = captionHTML;

            // Insert caption inside UAGB block or after regular image
            if (uagbBlock) {
                uagbBlock.appendChild(captionDiv);
            } else {
                img.parentNode.insertBefore(captionDiv, img.nextSibling);
            }
        }
    }
});
