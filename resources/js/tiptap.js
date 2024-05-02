import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import Link from '@tiptap/extension-link';
import TextAlign from '@tiptap/extension-text-align';

export function initTipTap(element, content) {
    let editorEl = element.querySelector('.editor')

    if(!editorEl) {
        return;
    }

    let editor = new Editor({
        element: editorEl,
        extensions: [
            StarterKit,
            Underline,
            Link.configure({
                openOnClick: false
            }),
            TextAlign.configure({
                types: ['heading', 'paragraph']
            })
        ],
        editorProps: {
            attributes: {
                class: 'tw-min-h-48 tw-max-h-48 tw-overflow-y-scroll tw-text-base tw-prose tw-max-w-none tw-px-3 tw-py-3 tw-outline-none'
            }
        },
        content: content,
        onSelectionUpdate({editor}) {
            updateButtons()
        },
        onUpdate({editor}) {
            updateButtons()
        }
    })

    const BoldButton = element.querySelector('.buttons .text-bold');
    const ItalicButton = element.querySelector('.buttons .text-italic')
    const UnderlineButton = element.querySelector('.buttons .text-underline')
    const Heading1Button = element.querySelector('.buttons .h1')
    const Heading2Button = element.querySelector('.buttons .h2')
    const Heading3Button = element.querySelector('.buttons .h3')
    const Heading4Button = element.querySelector('.buttons .h4')
    const OrderedListButton = element.querySelector('.buttons .list-ol')
    const BulletListButton = element.querySelector('.buttons .list-ul')
    const LinkButton = element.querySelector('.buttons .insert-link')
    const AlignLeftButton = element.querySelector('.buttons .paragraph-align-left')
    const AlignCenterButton = element.querySelector('.buttons .paragraph-align-center')
    const AlignRightButton = element.querySelector('.buttons .paragraph-align-right')

    const linkModal = element.querySelector('.link-modal')
    const linkModalSaveButton = element.querySelector('.link-modal .link-modal-save')
    const linkModalRemoveButton = element.querySelector('.link-modal .link-modal-remove')
    const linkModalUrlField = element.querySelector('.link-modal input[name="url"]')

    if (BoldButton) {
        BoldButton.addEventListener('click', () => {
            editor.chain().focus().toggleBold().run();
            updateButton(BoldButton, 'bold')
        });
    }

    if (ItalicButton) {
        ItalicButton.addEventListener('click', () => {
            editor.chain().focus().toggleItalic().run()
            updateButton(ItalicButton, 'italic')
        })
    }

    if (UnderlineButton) {
        UnderlineButton.addEventListener('click', () => {
            editor.chain().focus().toggleUnderline().run()
            updateButton(UnderlineButton, 'underline')
        })
    }

    if (Heading1Button) {
        Heading1Button.addEventListener('click', () => {
            editor.chain().focus().toggleHeading({level:1}).run()
            updateButton(Heading1Button, 'heading', {level:1})
        })
    }

    if (Heading2Button) {
        Heading2Button.addEventListener('click', () => {
            editor.chain().focus().toggleHeading({level:2}).run()
            updateButton(Heading2Button, 'heading', {level:2})
        })
    }

    if (Heading3Button) {
        Heading3Button.addEventListener('click', () => {
            editor.chain().focus().toggleHeading({level:3}).run()
            updateButton(Heading3Button, 'heading', {level:3})
        })
    }

    if (Heading4Button) {
        Heading4Button.addEventListener('click', () => {
            editor.chain().focus().toggleHeading({level:4}).run()
            updateButton(Heading4Button, 'heading', {level:4})
        })
    }

    if (OrderedListButton) {
        OrderedListButton.addEventListener('click', () => {
            editor.chain().focus().toggleOrderedList().run();
            updateButton(OrderedListButton, 'orderedList')
        });
    }

    if (BulletListButton) {
        BulletListButton.addEventListener('click', () => {
            editor.chain().focus().toggleBulletList().run();
            updateButton(BulletListButton, 'bulletList')
        });
    }

    if (LinkButton) {
        LinkButton.addEventListener('click', () => {
            openLinkModal()

            if(editor.isActive('link')) {
                linkModalUrlField.value = editor.getAttributes('link').href
            }
        })

        linkModalRemoveButton.addEventListener('click', () => {
            removeLink()
        })

        linkModalSaveButton.addEventListener('click', () => {
            saveLink()
        })

        linkModal.querySelector('.link-modal-close').addEventListener('click', () => {
            closeLinkModal()
        })
    }

    if(AlignLeftButton) {
        AlignLeftButton.addEventListener('click', () => {
            if(editor.isActive({textAlign: 'left'})) {
                editor.chain().focus().unsetTextAlign().run()
            } else {
                editor.chain().focus().setTextAlign('left').run()
            }

            updateButton(AlignLeftButton, {textAlign: 'left'})
        });
    }

    if(AlignCenterButton) {
        AlignCenterButton.addEventListener('click', () => {
            if(editor.isActive({textAlign: 'center'})) {
                editor.chain().focus().unsetTextAlign().run()
            } else {
                editor.chain().focus().setTextAlign('center').run()
            }

            updateButton(AlignCenterButton, {textAlign: 'center'})
        });
    }

    if(AlignRightButton) {
        AlignRightButton.addEventListener('click', () => {
            if(editor.isActive({textAlign: 'right'})) {
                editor.chain().focus().unsetTextAlign().run()
            } else {
                editor.chain().focus().setTextAlign('right').run()
            }

            updateButton(AlignRightButton, {textAlign: 'right'})
        });
    }

    function openLinkModal() {
        linkModal.classList.remove('tw-hidden')
    }

    function closeLinkModal() {
        linkModal.classList.add('tw-hidden')
        linkModalUrlField.value = null
    }

    function removeLink() {
        editor.chain().focus().extendMarkRange('link').unsetLink().run()
        closeLinkModal()
    }

    function saveLink() {
        if(!linkModalUrlField.value) {
            alert('Please enter a URL')
            return
        }

        editor.chain().focus().extendMarkRange('link').setLink({
            href: linkModalUrlField.value
        }).run()

        closeLinkModal()
    }

    function updateButton(button, eventName, eventArgs = {}) {
        console.log(editor.isActive(eventName))

        if (editor.isActive(eventName, eventArgs)) {
            button.classList.add('tw-bg-gray-200', 'rounded');
        } else {
            button.classList.remove('tw-bg-gray-200', 'rounded');
        }
    }

    function updateButtons() {
        updateButton(BoldButton, 'bold')
        updateButton(ItalicButton, 'italic')
        updateButton(Heading1Button, 'heading', {level:1})
        updateButton(Heading2Button, 'heading', {level:2})
        updateButton(Heading3Button, 'heading', {level:3})
        updateButton(Heading4Button, 'heading', {level:4})
        updateButton(UnderlineButton, 'underline')
        updateButton(OrderedListButton, 'orderedList')
        updateButton(BulletListButton, 'bulletList')
        updateButton(LinkButton, 'link')
    }

    return editor
}



