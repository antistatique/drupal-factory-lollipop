ARG BASE_IMAGE_TAG=8.9
FROM wengerk/drupal-for-contrib:${BASE_IMAGE_TAG}

ARG BASE_IMAGE_TAG
ENV BASE_IMAGE_TAG=${BASE_IMAGE_TAG}

# Register the Drupal and DrupalPractice Standard with PHPCS.
RUN ./vendor/bin/phpcs --config-set installed_paths \
    `pwd`/vendor/drupal/coder/coder_sniffer

# Copy the Analyzer definition files to ease run.
COPY phpcs.xml.dist phpmd.xml ./
