// public/js/comment-interactions.js

document.addEventListener('click', function(e) {

    // === LIKE BUTTON ===
    const likeBtn = e.target.closest('.love-btn');
    if (likeBtn) {
        e.preventDefault();
        const id = likeBtn.dataset.id;
        const count = likeBtn.querySelector('.like-count');
        const token = document.querySelector('meta[name="csrf-token"]').content;

        if (!token) {
            alert('CSRF token not found. Please refresh the page.');
            return;
        }

        // Disable button temporarily
        likeBtn.disabled = true;
        likeBtn.style.opacity = '0.6';

        fetch(`/comments/${id}/like`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token,
                "Accept": "application/json"
            }
        })
        .then(res => {
            console.log('Like response status:', res.status);
            if (!res.ok) {
                throw new Error('Failed to like');
            }
            return res.json();
        })
        .then(data => {
            console.log('Like data:', data);
            
            if (data.success) {
                // Update count
                count.textContent = data.likes_count;
                
                // Toggle heart color
                const svg = likeBtn.querySelector('svg');
                if (svg) {
                    if (data.is_liked) {
                        svg.classList.add('fill-red-500', 'text-red-500');
                        svg.classList.remove('fill-none');
                    } else {
                        svg.classList.remove('fill-red-500', 'text-red-500');
                        svg.classList.add('fill-none');
                    }
                }
                
                // Animation
                likeBtn.classList.add('scale-110');
                setTimeout(() => {
                    likeBtn.classList.remove('scale-110');
                }, 200);
            }
            
            // Re-enable button
            likeBtn.disabled = false;
            likeBtn.style.opacity = '1';
        })
        .catch(err => {
            console.error('Like error:', err);
            alert('Gagal melakukan like: ' + err.message);
            
            // Re-enable button
            likeBtn.disabled = false;
            likeBtn.style.opacity = '1';
        });

        return;
    }

    // === REPLY BUTTON ===
    const replyBtn = e.target.closest('.reply-btn');
    if (replyBtn) {
        e.preventDefault();
        const id = replyBtn.dataset.id;
        const form = document.getElementById("reply-form-" + id);
        
        if (form) {
            form.classList.toggle("hidden");
            
            if (!form.classList.contains("hidden")) {
                const textarea = form.querySelector('textarea');
                if (textarea) textarea.focus();
            }
        }
        return;
    }

    // === CANCEL REPLY ===
    const cancelBtn = e.target.closest('.cancel-reply-btn');
    if (cancelBtn) {
        e.preventDefault();
        const id = cancelBtn.dataset.id;
        const form = document.getElementById("reply-form-" + id);
        
        if (form) {
            form.classList.add("hidden");
            const textarea = form.querySelector('textarea');
            if (textarea) textarea.value = '';
        }
        return;
    }

    // === SHARE BUTTON ===
    const shareBtn = e.target.closest('.share-btn');
    if (shareBtn) {
        e.preventDefault();
        const url = shareBtn.dataset.url;
        
        const fullUrl = window.location.origin + (url.startsWith('/') ? url : '/' + url);
        
        navigator.clipboard.writeText(fullUrl).then(() => {
            const textSpan = shareBtn.querySelector('span:last-child');
            if (textSpan) {
                const originalText = textSpan.textContent;
                textSpan.textContent = 'Link disalin!';
                shareBtn.classList.add('text-green-500');
                
                setTimeout(() => {
                    textSpan.textContent = originalText;
                    shareBtn.classList.remove('text-green-500');
                }, 2000);
            }
        }).catch(err => {
            console.error('Failed to copy:', err);
            alert('Gagal menyalin link');
        });
        return;
    }

    // === DELETE BUTTON ===
    const deleteBtn = e.target.closest('.delete-btn');
    if (deleteBtn) {
        e.preventDefault();
        const id = deleteBtn.dataset.id;
        
        if (!confirm('Yakin ingin menghapus komentar ini?')) {
            return;
        }
        
        const token = document.querySelector('meta[name="csrf-token"]').content;
        
        deleteBtn.disabled = true;
        deleteBtn.style.opacity = '0.5';
        
        fetch(`/comments/${id}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token,
                "Accept": "application/json"
            }
        })
        .then(res => {
            console.log('Delete response status:', res.status);
            if (!res.ok) {
                return res.json().then(err => {
                    throw new Error(err.message || 'Gagal menghapus komentar');
                });
            }
            return res.json();
        })
        .then(data => {
            console.log('Delete response:', data);
            
            if (data.success) {
                const commentEl = document.getElementById(`comment-${id}`);
                if (commentEl) {
                    commentEl.style.transition = 'opacity 0.3s';
                    commentEl.style.opacity = '0';
                    setTimeout(() => {
                        commentEl.remove();
                        location.reload(); // Reload to update counts
                    }, 300);
                }
            } else {
                throw new Error(data.message || 'Gagal menghapus komentar');
            }
        })
        .catch(err => {
            console.error('Delete error:', err);
            alert('Gagal menghapus komentar: ' + err.message);
            
            deleteBtn.disabled = false;
            deleteBtn.style.opacity = '1';
        });
        
        return;
    }

});