<script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "Organization",
        "url": "{$meta->url}",
        "name": "{$meta->title}",
        "image": "{$meta->image}",
        "description": "{$meta->description}",
        "telephone": "{$phone->title}",
        "email": "{$email->title}",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "",
            "streetAddress": "",
            "postalCode": ""
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "sales",
            "name": "Менеджер",
            "telephone": "{$phone->title}"
        }
    }
</script>
